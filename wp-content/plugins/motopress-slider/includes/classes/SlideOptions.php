<?php

require_once dirname(__FILE__) . '/MainOptions.php';
require_once dirname(__FILE__) . '/LayerPresetOptions.php';

//TODO: Make `layers` the new entity `MPSLLayer`

class MPSLSlideOptions extends MPSLMainOptions {
    private $sliderId = null;
    private $sliderAlias = null;
    private $slideOrder = null;
    private $layers = array();
    private $layerOptions = null;
    private $preview = false;
    private $edit = false;
//    private $layerStyleOptions = null;
	/*
	 * @var $layerPresets MPSLLayerPresetOptions
	 */
    public $layerPresets = null;
	private static $instance = null;

	/*
	 * @var (int) $id - Slider ID
	 * @var (boolean) $preview - Preview flag
	 * @var (boolean) $edit - Edit or View slide flag (use with $preview = true)
	 */
    function __construct($id = null, $preview = false, $edit = false) {
        parent::__construct();

        $this->preview = $preview;
        $this->edit = $edit;

        $this->options = include($this->getSettingsPath());
        $this->prepareOptions($this->options);

        $this->layerOptions = include($this->getSettingsPath('layer'));
        $this->prepareOptions($this->layerOptions);

	    $this->layerPresets = MPSLLayerPresetOptions::getInstance($this->preview);

        if (is_null($id)) {
            $this->overrideOptions(null, false);
        } else {
            $loaded = $this->load($id);

            if (!$loaded) {
                // TODO: Throw error
//                _e('Record not found', MPSL_TEXTDOMAIN);
            }
        }
    }

	public static function getInstance($id = null, $preview = false, $edit = false) {
		if (null === self::$instance) {
			self::$instance = new self($id, $preview, $edit);
		}
		return self::$instance;
	}

    protected function load($id) {
        global $wpdb;

        $result = $wpdb->get_row(sprintf(
            'SELECT * FROM %s WHERE id = %d',
            $wpdb->prefix . ($this->preview && !$this->edit ? parent::SLIDES_PREVIEW_TABLE : parent::SLIDES_TABLE),
            (int) $id
        ), ARRAY_A);

        if (is_null($result)) return false;

        $this->id = (int) $id;
        $this->sliderId = (int) $result['slider_id'];
        $this->sliderAlias = MPSLSliderOptions::getAliasById($this->sliderId);
        $this->slideOrder = (int) $result['slide_order'];
        $this->overrideOptions(json_decode($result['options'], true), false);
        $this->overrideLayers(json_decode($result['layers'], true));

        return true;
    }

    public function overrideLayers($layers = null) {
        $defaults = $this->getDefaults($this->layerOptions);

	    if (!empty($layers)) {
            foreach($layers as $layerKey => $layer) {
                $layers[$layerKey] = array_merge($defaults, $layer);
//                $layers[$layerKey] = array_replace_recursive($defaults, $layer);

	            $layers[$layerKey]['private_styles'] = $this->layerPresets->override($layers[$layerKey]['private_styles'], true);
	            if ($layers[$layerKey]['preset'] === 'private' && !$layers[$layerKey]['private_preset_class']) {
		            $this->layerPresets->incLastPrivatePresetId();
		            $layers[$layerKey]['private_preset_class'] = $this->layerPresets->getLastPrivatePresetClass();
	            }

                // update attached image url
                if (isset($layers[$layerKey]['image_id']) && !empty($layers[$layerKey]['image_id'])) {
                    $image_url = wp_get_attachment_url($layers[$layerKey]['image_id']);
                    if (false === $image_url) {
                        $image_url = '?';
                    }
                    $layers[$layerKey]['image_url'] = $image_url;
                }
            }
        }
        $this->layers = $layers;
    }

    public function overrideOptions($options = null, $isGrouped = true) {
        if (isset($options['bg_image_id']) && !empty($options['bg_image_id'])) {
            $image_url = wp_get_attachment_url($options['bg_image_id']);
            if (false === $image_url) {
                $image_url = '?';
            }
            $options['bg_internal_image_url'] = $image_url;
        }
        parent::overrideOptions($options, $isGrouped);
    }

    public function create($sliderId = null) {
        global $wpdb;

        // Update options with new data
        $this->overrideOptions();

        // Define query data
        $qTable = $wpdb->prefix . self::SLIDES_TABLE;

        $order = $this->getNextOrder($sliderId);

        $qData = array(
            'slider_id' => $sliderId,
            'slide_order' => $order,
            'options' => json_encode($this->getOptionValues()),
            'layers' => json_encode(array())
        );
        $qFormats = array('%d', '%d', '%s', '%s');

        // Exec query
        $wpdb->hide_errors();
        $result = $wpdb->insert($qTable, $qData, $qFormats);
        if ($result === false) {
            mpslSetError(__('Slide is not created. Error: ', MPSL_TEXTDOMAIN) . $wpdb->last_error);
        }
        $id = ($result) ? $wpdb->insert_id : null;
        $this->id = (int) $id;
        $this->setGeneratedByIdTitle();
        $this->update();

        wp_send_json(array('result' => $result, 'id' => $id));
    }

    public function prepareLayersForImport(&$layers, $presetClasses = array()) {
	    $presetsExists = count($presetClasses);

		foreach ($layers as &$layer) {
			// Update preset class
			if ($presetsExists && isset($layer['preset']) && $layer['preset']) {
				if (array_key_exists($layer['preset'], $presetClasses)) {
					$layer['preset'] = $presetClasses[$layer['preset']];
				}
			}

			// Private preset
			$this->regenerateLayerPrivatePreset($layer);
		}
    }

    public function import($sliderId) {
        global $wpdb;
        $qTable = $wpdb->prefix . self::SLIDES_TABLE;
        $order = $this->getNextOrder($sliderId);
        $qData = array(
            'slider_id' => $sliderId,
            'slide_order' => $order,
            'options' => json_encode($this->getOptionValues()),
            'layers' => json_encode($this->layers)
        );
        $qFormats = array('%d', '%d', '%s', '%s');
        $wpdb->hide_errors();
        $this->setId(null);
        $result = $wpdb->insert($qTable, $qData, $qFormats);
        $id = ($result) ? $wpdb->insert_id : null;
        $this->id = (int) $id;
        return $id;
    }

    public function getNextOrder($sliderId){
        global $wpdb;
        $qTable = $wpdb->prefix . self::SLIDES_TABLE;
        $order = $wpdb->get_var(sprintf(
            "SELECT MAX(slide_order) FROM %s WHERE slider_id=%d",
            $qTable, $sliderId
        ));
        return is_null($order) ? 1 : $order + 1;
    }

    public function update() {
        global $wpdb;

//	    update_option(MPSLLayerPresetOptions::LAST_PRIVATE_PRESET_ID_OPT, $this->layerPresets->getLastPrivatePresetId());

	    $options = $this->getOptionValues();
//	    $presets = $this->layerPresets->getPresets();
	    $presets = $this->layerPresets->getAllPresets();
	    $fonts = array();

	    foreach ($this->layers as &$layer) {
		    // Get used fonts
		    if ($presetClass = $layer['preset']) {
			    if ($presetClass === 'private') {
					$styles = $layer['private_styles'];
			    } else if (isset($presets[$presetClass])) {
				    $styles = $presets[$presetClass];
			    }
			    if (isset($styles)) $fonts = array_merge_recursive($fonts, $this->layerPresets->getFontsByPreset($styles));
		    }

		    $layer['private_styles'] = $this->layerPresets->clearPreset($layer['private_styles']);
	    }
	    // Set used fonts
	    $options['fonts'] = MPSLLayerPresetOptions::fontsUnique($fonts);

        // Define query data
        $qTable =  $wpdb->prefix . ($this->preview ? self::SLIDES_PREVIEW_TABLE : self::SLIDES_TABLE);
        $qData = array(
            'options' => json_encode($options),
            'layers' => json_encode($this->layers)
        );
        $qFormats = array('%s', '%s');

        // Exec query
//        if (is_null($id)) {
//            $result = $wpdb->insert($qTable, $qData, $qFormats);
//            $id = ($result) ? $wpdb->insert_id : null;
//        } else {
            $wpdb->hide_errors();

            if ($this->preview) {
	            $slideInsertResult = false;
                $truncateResult = $wpdb->query(sprintf('TRUNCATE TABLE %s', $qTable));
				if ($truncateResult !== false) {
					$qData['id'] = $this->id;
					$qData['slider_id'] = $this->sliderId;
					$qData['slide_order'] = $this->slideOrder;
					$slideInsertResult = $wpdb->insert($qTable, $qData);
				}
	            return $slideInsertResult;

            } else {
                return $wpdb->update($qTable, $qData, array('id' => $this->id), $qFormats);
            }

//        }
    }

    public function delete() {
        global $wpdb;
        $wpdb->hide_errors();
        $deleteResult = $wpdb->delete($wpdb->prefix . self::SLIDES_TABLE, array('id' => $this->id));

	    $this->layerPresets->updatePrivateStyles();

	    return $deleteResult;
    }

    public function duplicateSlide($slideId, $sliderId = null) {
        global $wpdb;
        $wpdb->hide_errors();
        $db = MPSliderDB::getInstance();

        $slide = $db->getSlide($slideId, array('slider_id', 'slide_order', 'options', 'layers'));
        if (is_null($slide)) {
            mpslSetError(__('Slide ID is not set.', MPSL_TEXTDOMAIN));
        }
        $order = $wpdb->get_var(sprintf(
            "SELECT MAX(slide_order) FROM %s WHERE slider_id=%d",
            $wpdb->prefix . parent::SLIDES_TABLE, is_null($sliderId) ? $this->sliderId : $sliderId
        ));
        $order = is_null($order) ? 0 : $order + 1;

        if (!is_null($sliderId)) $slide['slider_id'] = $sliderId;
        $slide['slide_order'] = $order;
        $options = json_decode($slide['options'], true);
        $layers = json_decode($slide['layers'], true);

        if ($options !== false && isset($options['title'])) {
            if (is_null($sliderId)) $options['title'] = __('Duplicate of ', MPSL_TEXTDOMAIN) . $options['title'];
            $slide['options'] = json_encode($options);

	        // Prepare layers
	        if ($layers !== false) {
		        foreach ($layers as &$layer) {
			        $this->regenerateLayerPrivatePreset($layer);
		        }
		        $slide['layers'] = json_encode($layers);
	        }
        }

	    $result = $wpdb->insert($wpdb->prefix . parent::SLIDES_TABLE, $slide);

	    return $result === false ? false : $wpdb->insert_id;
    }

	private function regenerateLayerPrivatePreset(&$layer) {
        if (
	        !isset($layer['private_preset_class'])
	        || !$layer['private_preset_class']
	        || preg_match('/^' . MPSLLayerPresetOptions::PRIVATE_PRESET_PREFIX . '[0-9]+$/', $layer['private_preset_class'])
        ) {
	        $this->layerPresets->incLastPrivatePresetId();
	        $layer['private_preset_class'] = $this->layerPresets->getLastPrivatePresetClass();
        }
	}

    public function getSliderAttrs() {
        $db = MPSliderDB::getInstance();
        $slider = $db->getSlider($this->sliderId);
        $slider['options'] = json_decode($slider['options']);
        return $slider;
    }

    public function getAttributes() {
        return array(
            'id' => $this->id,
            'slider_id' => $this->sliderId,
            'slide_order' => $this->slideOrder,
        );
    }

    public function render() {
        global $mpsl_settings;
        $options = $this->getOptions(true);
        include $this->getViewPath();
    }

    public function renderLayer() {
        global $mpsl_settings;
        $slider = new MPSLSliderOptions($this->id);
        include($this->getViewPath('layer'));
    }

    public function getSliderId() {
        return $this->sliderId;
    }

    public function getSlideOrder() {
        return $this->slideOrder;
    }

    public function getLayers() {
        return $this->layers;
    }

    public function getLayersForExport(&$internalResources){
        $options = array();
        $layers = $this->layers;
        foreach ($layers as &$layer) {
            foreach ($layer as $optionName => $optionValue) {
                switch ($optionName) {
                    case 'image_id' :
                        if (!empty($optionValue)) {
                            if (!isset($internalResources[$optionValue])) {
                                $internalResources[$optionValue] = array();
                                $internalResources[$optionValue]['value'] = wp_get_attachment_url($optionValue);
                            }
                            $layer[$optionName] = array(
                                'need_update' => true,
                                'old_value' => $optionValue
                            );
                        }
                        break;

	                case 'private_styles':
		                $layer[$optionName] = $this->layerPresets->clearPreset($layer[$optionName]);
		                break;
                }
            }
        }
        return $layers;
    }

    public function setLayers($layers) {
        $this->layers = $layers;
    }

    public function getLayerOptions($grouped = false) {
	    if ($grouped) {
			return $this->layerOptions;
		} else {
			$options = array();
			foreach ($this->layerOptions as $grp) {
				$options = array_merge($options, $grp['options']);
			}
			return $options;
		}
    }

	public function isSliderVisible() {
		$optionValues = $this->getOptionValues();

		$isPublished = isset($optionValues['status']) && $optionValues['status'] === 'published';
        $isNeedLogin = isset($optionValues['need_logged_in']) && $optionValues['need_logged_in'];
        $canCurrentUserView = $isNeedLogin ? is_user_logged_in() : true;

        $isCurDateInVisiblePeriod = true;
        if (isset($optionValues['date_from']) && $optionValues['date_from'] !== '') {
            $dateFrom = strtotime($optionValues['date_from']);
            if (false !== $dateFrom && -1 !== $dateFrom && current_time('timestamp') < $dateFrom) {
                $isCurDateInVisiblePeriod = false;
            }
        }
        if (isset($optionValues['date_until']) && $optionValues['date_until'] !== '') {
            $dateUntil = strtotime($optionValues['date_until']);
            if (false !== $dateUntil && -1 !== $dateUntil && current_time('timestamp') > $dateUntil) {
                $isCurDateInVisiblePeriod = false;
            }
        }

		return ($isPublished && $canCurrentUserView && $isCurDateInVisiblePeriod);
	}

	protected function getSettingsFileName() {
		return 'slide';
	}

	protected function getViewFileName() {
		return 'slide';
	}

    public function setGeneratedByIdTitle(){
        $newTitle = $this->getTitle() . '-' . $this->id;
        $this->setTitle($newTitle);
    }

    public function getTitle(){
        return $this->options['main']['options']['title']['value'];
    }

    public function setTitle($title){
        $this->options['main']['options']['title']['value'] = $title;
    }

    public function getSiblingsSlides() {
        $db = MPSliderDB::getInstance();

        if (!$db->isSliderExists($this->sliderId)) return false;

        $slides = $db->getSiblings($this->sliderId);

        foreach ($slides as $key => $value) {

            if($value['id'] == $this->id){
                $nextEl = current(array_slice($slides, array_search($key, array_keys($slides)) + 1, 1));
                $prevEl = current(array_slice($slides, array_search($key, array_keys($slides)) - 1, 1));

                return array(
                        'next' => $nextEl ? $nextEl['id'] : $slides[0]['id'],
                        'prev' => $prevEl ? $prevEl['id'] : $value['id']
                    );
            }
        }
    }

	public function getUsedPresetClasses() {
		$classes = array();
		foreach ($this->layers as $layer) {
			if (isset($layer['preset']) && $layer['preset'] && $layer['preset'] !== 'private') {
			    $classes[] = $layer['preset'];
		    }
		}
		return array_unique($classes);
	}
}
