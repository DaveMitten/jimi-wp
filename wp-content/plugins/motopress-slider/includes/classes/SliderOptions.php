<?php

include_once dirname(__FILE__) . '/MainOptions.php';

class MPSLSliderOptions extends MPSLMainOptions {
    private $title = null;
    private $alias = null;
    private $preview = false;
    private $slidePreview = false;
    private $previewSlideId = null;
    private $edit = false;
//    private $previewOptions = null;
    /**
     * @param null $id
     * @param mixed bool|int $preview Slide ID for preview or FALSE for default functionality
     */
    function __construct($id = null, $preview = false, $slidePreview = false, $edit = false) { //, $options = null
        parent::__construct();

        $this->preview = $preview;
        $this->edit = $edit;
        $this->slidePreview = $slidePreview;


        $this->options = include($this->getSettingsPath());
        $this->prepareOptions($this->options);

//        $this->previewOptions = $options;

        if (is_null($id)) {
            $this->overrideOptions(null, false);
        } else {
            $this->load($id);
        }
//        var_dump($this->options['main']['options']['start_slide']);die;
    }

    public function setPreviewSlideId($id){
        $this->previewSlideId = $id;
    }

    protected function load($id) {
        global $wpdb;

        $result = $wpdb->get_row(sprintf(
            'SELECT * FROM %s WHERE id = %d',
            $wpdb->prefix . ($this->preview  && !$this->edit ? parent::SLIDERS_PREVIEW_TABLE : parent::SLIDERS_TABLE),
            (int) $id
        ), ARRAY_A);
        if (is_null($result)) return false;

        $this->id = (int) $id;
        $this->title = $result['title'];
        $this->alias = $result['alias'];

//        $options = is_null($this->previewOptions) ?  : json_decode($this->previewOptions, true);

        $options = json_decode($result['options'], true);
        $this->overrideOptions($options, false);

        return true;
    }

    public function loadByAlias($alias) {
        global $wpdb;

        $result = $wpdb->get_row(sprintf(
            'SELECT * FROM %s WHERE alias LIKE \'%s\'',
            $wpdb->prefix . ($this->preview ? parent::SLIDERS_PREVIEW_TABLE : parent::SLIDERS_TABLE),
            $alias
        ), ARRAY_A);

        if (is_null($result)) return false;

        $this->id = (int) $result['id'];
        $this->title = $result['title'];
        $this->alias = $result['alias'];

//        $options = is_null($this->previewOptions) ? json_decode($result['options'], true) : json_decode($this->previewOptions, true);
        $options = json_decode($result['options'], true);
        $this->overrideOptions($options, false);

        return true;
    }


    public function setOptions($options){


//        var_dump($options); die;
    }

    public function update() {
        global $wpdb;

        // Define query data
        $qTable = $wpdb->prefix . ($this->preview ? parent::SLIDERS_PREVIEW_TABLE : parent::SLIDERS_TABLE);
        $qData = array(
            'title' => $this->getTitle(),
            'alias' => $this->getAlias(),
            'options' => json_encode($this->getOptionValues())
        );
        $qFormats = array('%s', '%s', '%s');

        // Exec query
//        return $wpdb->update($qTable, $qData, array('id' => $this->getId()), $qFormats);

        if ($this->preview) {
            $sliderInsertResult = false;
            $truncateResult = $wpdb->query(sprintf('TRUNCATE TABLE %s', $qTable));
            if ($truncateResult !== false) {
                $qData['id'] = $this->getId();
                $sliderInsertResult = $wpdb->insert($qTable, $qData);
            }
            return $sliderInsertResult;

        } else {
            return $wpdb->update($qTable, $qData, array('id' => $this->getId()), $qFormats);
        }
    }

    public function getTitle() {
//        return $this->options['main']['options']['title']['value'];
        return $this->title;
    }
    public function setTitle($title) {
        $this->title = $title;
        $this->updateOption('main', 'title', $title);
    }

    public function getAlias() {
//        return $this->options['main']['options']['alias']['value'];
        return $this->alias;
    }
    public function setAlias($alias) {
        $this->alias = $alias;
        $this->updateOption('main', 'alias', $alias);
    }
    public function getAttributes() {
        return array(
            'id' => $this->id,
            'title' => $this->title,
            'alias' => $this->alias,
        );
    }
    public function getFullSliderData($slideId = null, $isEditor = false) {
        $slidesData = array();
        if (is_null($slideId)){
            $slides = $this->getSlides();
        } else {
            $slide = $this->getSlide($slideId);
            $slides = array($slide);
        }
        $counter = 1;
        foreach( (array) $slides as $key => $slide) {
	        if ($isEditor) {
		        $slideObj = MPSLSlideOptions::getInstance((int) $slide['id']);
	        } else {
		        $slideObj = new MPSLSlideOptions((int) $slide['id'], ($this->slidePreview && $this->previewSlideId == $slide['id']));
	        }
            $slideData['options'] = $slideObj->getOptionValues();

	        $slideVisible = $isEditor || $slideObj->isSliderVisible();
	        if (!$slideVisible && !($this->slidePreview && $this->previewSlideId == $slide['id'])) continue;

            $slideData['layers'] = $slideObj->getLayers();
            $slideData['active'] = ($this->slidePreview && $this->previewSlideId == $slide['id']) || (!$this->slidePreview && $this->options['main']['options']['start_slide']['value'] == $counter);
            $slidesData[] = $slideData;
            $counter++;
        }
        $fullSliderData = array(
            'options' => $this->getOptionValues(),
            'slides' => $slidesData
        );
        return $fullSliderData;
    }

    public function getExportSliderData(&$internalResources){
        $slidesData = array();        
        $slides = $this->getSlides();
	    $presetClasses = array();
        foreach( (array) $slides as $slide) {
            $slideObj = new MPSLSlideOptions((int) $slide['id']);
            $slideData['options'] = $slideObj->getOptionValuesForExport($internalResources);
            $slideData['layers'] = $slideObj->getLayersForExport($internalResources);
            $slidesData[] = $slideData;

	        $presetClasses = array_merge($presetClasses, $slideObj->getUsedPresetClasses());
        }
        $exportSliderData = array(
            'options' => $this->getOptionValues(),
            'slides' => $slidesData
        );        
        return array(
	        'slider' => $exportSliderData,
	        'preset_classes' => array_unique($presetClasses)
        );
    }

    public function overrideOptions($options = null, $isGrouped = true) {
        parent::overrideOptions($options, $isGrouped);
        if ($isGrouped) {
            if (isset($options['main']['title'])) {
                $this->setTitle($options['main']['title']);
            }
            if (isset($options['main']['alias'])) {
                $this->setAlias($options['main']['alias']);
            }
        } else {
            if (isset($options['title'])) {
                $this->setTitle($options['title']);
            }
            if (isset($options['alias'])) {
                $this->setAlias($options['alias']);
            }
        }
    }

    public function create() {
        global $wpdb;

        // TODO: Flash messages
//        if (!isset($this->options['title'])) return false;
//        if (!isset($this->options['alias'])) return false;

        // Update options with new data
//        $this->overrideOptions($options, true);

        // Define query data
        $qTable = $wpdb->prefix . self::SLIDERS_TABLE;
        $qData = array(
            'title' => $this->getTitle(),
            'alias' => $this->getAlias(),
            'options' => json_encode($this->getOptionValues())
        );
        $qFormats = array('%s', '%s', '%s');

        // Exec query

        $result = $wpdb->insert($qTable, $qData, $qFormats);
        if (false !== $result) {
            $id = $wpdb->insert_id;
            $this->setId($id);
            return $id;
        } else {
            return false;
        }
    }

    public function isNotValidOptions(){
        $errors = array();
        if (is_array($this->options)) {
            foreach($this->options as $groupName => $group) {
                if (is_array($group['options'])) {
                    foreach($group['options'] as $optionName => $option) {
                        $error = $this->isNotValidOption($option);
                        if ($error) {
                            $errors[] = $error;
                        }
                    }
                }
            }
        }
        if (!empty($errors)) {
            return $errors;
        } else {
            return false;
        }
    }

    public function getSlides($sliderId = null, $decodeFields = array()) {
        $db = MPSliderDB::getInstance();
        $slides = $db->getSlidesBySlider(is_null($sliderId) ? $this->getId() : $sliderId, $decodeFields);
//        foreach ($slides as &$slide) {
//            $options = json_decode($slide['options'], true);
//            if ($options) {
//                $slide['title'] = (isset($options['title'])) ? $options['title'] : false;
//            }
//        }
        return $slides;
    }

    public function getSlide($id) {
        global $wpdb, $mpsl_settings;
        $query = sprintf(
            'SELECT * FROM %s WHERE id=%d ORDER BY slide_order ASC',
            $mpsl_settings['slides_table'],
            $id
        );
        $slide = $wpdb->get_row($query, ARRAY_A);
        return $slide;
    }

    public function isNotValidOption($option){
        if (empty($option)) {
            return __('Empty option ', MPSL_TEXTDOMAIN) . $option['label'];
        }
        return false;
    }

    public function getSliderEditUrl(){
        global $mpsl_settings;
        $menuUrl = menu_page_url($mpsl_settings['plugin_name'], false);
        $sliderEditUrl = add_query_arg(array('view' => 'slider','id' => $this->getId()), $menuUrl);
        return $sliderEditUrl;
    }

    public function delete() {
        global $wpdb;
        $resultSlides = $wpdb->delete($wpdb->prefix . self::SLIDES_TABLE, array('slider_id' => $this->getId()));
        $resultSlider = $wpdb->delete($wpdb->prefix . self::SLIDERS_TABLE, array('id' => $this->getId()));

	    $layerPresetsObj = MPSLLayerPresetOptions::getInstance();
	    $layerPresetsObj->updatePrivateStyles();

        // Note that since both 0 and FALSE may be returned $wpdb->query
        // http://php.net/manual/en/language.types.boolean.php#language.types.boolean.casting
        return ($resultSlides !== false) && ($resultSlider !== false);
    }

    public function duplicate() {
	    $result = array('slide' => false, 'slideId' => null, 'slides' => false);

        $oldAlias = $this->getAlias();
        $oldTitle = $this->getTitle();
        $newTitle = 'Duplicate of ' . $oldTitle;
        $uniqueAlias = $this->generateUniqueAlias();
        $this->setAlias($uniqueAlias);
        $this->setTitle($newTitle);
        $oldId = $this->getId();
        $newId = $this->create();

        if (false !== $newId) {
			/*global $wpdb;
            $selectQuery = sprintf("SELECT %d, slide_order, options, layers FROM %s WHERE slider_id = %d", $newId, $wpdb->prefix . self::SLIDES_TABLE, $oldId);
            $query = sprintf('INSERT INTO %s (slider_id, slide_order, options, layers) (' . $selectQuery . ')', $wpdb->prefix . self::SLIDES_TABLE);
            $wpdb->hide_errors();
            $res = $wpdb->query($query);*/

	        $result['slide'] = true;
	        $result['slide_id'] = $newId;

	        global $wpdb;
	        $wpdb->hide_errors();
	        $slides = $this->getSlides($oldId); // TODO: Create and use another function (select only IDs)
	        $slidesRes = true;

	        foreach ($slides as $slide) {
		        $slideObj = new MPSLSlideOptions($slide['id']);
		        $slideDuplicateRes = $slideObj->duplicateSlide($slide['id'], $newId);
		        $slidesRes = $slidesRes && ($slideDuplicateRes !== false);
	        }
	        $result['slides'] = $slidesRes;
        }

	    return $result;
    }

    public function generateUniqueAlias($prefix = 'slider'){
        $uniqueAlias = uniqid($prefix);
        if ($this->isAliasExists($uniqueAlias)) {
            return $this->generateUniqueAlias($prefix);
        } else {
            return $uniqueAlias;
        }
    }
    
    public function makeAliasUnique(){                
        $alias = $this->alias;
        if ( !$this->isAliasValid($alias) ) {
            $alias = 'slider';
        }
        if ($this->isAliasExists($alias)) {
            $alias = $this->generateUniqueAlias($alias);
        }
        $this->setAlias($alias);                
    }

    public function setUniqueAliasIfEmpty(){
        if (is_null($this->getAlias())){
            $this->setAlias($this->generateUniqueAlias());
        }
    }

    public function render(){
        global $mpsl_settings;
        $slider = $this;
        $slider->setUniqueAliasIfEmpty();
        include($this->getViewPath());
    }

    public function isAliasValid(){
        $aliasPattern = "/^[-_a-zA-Z0-9]+$/";
        return preg_match($aliasPattern, $this->alias);
    }

    public function isTitleValid(){
        return !empty($this->title);
    }

    public static function isAliasExists($alias){
        global $wpdb;
        return !is_null($wpdb->get_row(sprintf('SELECT * FROM %s WHERE alias LIKE \'%s\'', $wpdb->prefix . parent::SLIDERS_TABLE, $alias)));
    }

    public static function getAliasById($id){
        global $wpdb;
        $result = $wpdb->get_row(sprintf('SELECT alias FROM %s WHERE id = %d', $wpdb->prefix . parent::SLIDERS_TABLE, $id), ARRAY_A);
        if (!is_null($result)) {
            return $result['alias'];
        } else{
            return null;
        }
    }

    public function isValidOptions(){
        if (!$this->isAliasValid())
            return false;
        if (!$this->isTitleValid())
            return false;
        foreach($this->options as $groupKey => $group){
            foreach($group['options'] as $optionName => $option) {
                if (isset($option['required']) && $option['required']) {
                    if (empty($option['value'])) {
                        return false;
                    }
                }
                if (isset($option['pattern']) && !preg_match($option['pattern'],$option['value'])) {
                    return false;
                }
            }
        }
        return true;
    }

    public static function isIdExists($id){
        global $wpdb;
        return !is_null($wpdb->get_row(sprintf('SELECT * FROM %s WHERE id = %d ', $wpdb->prefix . parent::SLIDERS_TABLE, $id)));
    }

	protected function getSettingsFileName() {
		return 'slider';
	}

	protected function getViewFileName() {
		return 'slider';
	}

}
