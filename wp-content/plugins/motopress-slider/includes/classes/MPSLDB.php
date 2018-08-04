<?php

class MPSliderDB {
    private $mpsl_settings;
    private $lastRowID;
	private static $instance = null;

    function __construct() {
        global $mpsl_settings;
        $this->mpsl_settings = &$mpsl_settings;
    }

	public static function getInstance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    public function getSliderList($fields = null, $keyField = null) {
        global $wpdb;

        if ($keyField and !in_array($keyField, array('id', 'alias'))) $keyField = null;
        $entity = 'slider';
        $singleValue = false;

        if (is_null($fields)) {
            $_fields = array('*');

        } elseif (is_array($fields)) {
//            if (count($fields) === 1) $singleValue = reset($fields);
            $_fields = $fields;
            if ($keyField and !in_array($keyField, $_fields)) $_fields[] = $keyField;

        } else {
            $fields = trim($fields);
            $singleValue = $fields;
            $_fields = array($fields);
            if ($keyField and $fields !== $keyField) $_fields[] = $keyField;
        }

        $query = sprintf(
            'SELECT %s FROM %s',
            implode(',', $_fields),
            $this->mpsl_settings["{$entity}s_table"]
        );

        $sliders = $wpdb->get_results($query, 'ARRAY_A');

        if ($keyField) {
            $_sliders = array();
            foreach ($sliders as $slider) {
                $_sliders[$slider[$keyField]] = $slider;
            }
            $sliders = $_sliders;
        }

        $decodeOptions = (in_array('options', $_fields) or in_array('*', $_fields));

        foreach ($sliders as $key => $slider) {
            if ($decodeOptions) {
                $sliders[$key]['options'] = json_decode($sliders[$key]['options'], true);
            }
            if ($singleValue) {
                $sliders[$key] = $sliders[$key][$singleValue];
            }
        }

        return $sliders;
    }

	public function getSlideList($fields = null, $keyField = null) {
        global $wpdb;

        if ($keyField and !in_array($keyField, array('id'))) $keyField = null;
        $entity = 'slide';
        $singleValue = false;

        if (is_null($fields)) {
            $_fields = array('*');

        } elseif (is_array($fields)) {
//            if (count($fields) === 1) $singleValue = reset($fields);
            $_fields = $fields;
            if ($keyField and !in_array($keyField, $_fields)) $_fields[] = $keyField;

        } else {
            $fields = trim($fields);
            $singleValue = $fields;
            $_fields = array($fields);
            if ($keyField and $fields !== $keyField) $_fields[] = $keyField;
        }

        $query = sprintf(
            'SELECT %s FROM %s',
            implode(',', $_fields),
            $this->mpsl_settings["{$entity}s_table"]
        );

        $slides = $wpdb->get_results($query, 'ARRAY_A');

        if ($keyField) {
            $_slides = array();
            foreach ($slides as $slider) {
                $_slides[$slider[$keyField]] = $slider;
            }
            $slides = $_slides;
        }

        $decodeOptions = (in_array('options', $_fields) or in_array('*', $_fields));
        $decodeLayers = (in_array('layers', $_fields) or in_array('*', $_fields));

        foreach ($slides as $key => $slider) {
            if ($decodeOptions) $slides[$key]['options'] = json_decode($slides[$key]['options'], true);
            if ($decodeLayers) {
	            $slides[$key]['layers'] = json_decode($slides[$key]['layers'], true);
	            if (!is_array($slides[$key]['layers'])) $slides[$key]['layers'] = array();
            }
            if ($singleValue) $slides[$key] = $slides[$key][$singleValue];
        }

        return $slides;
    }

    /**
     * @param $id - Slide(r) id
     * @param null | string | array $fields - Slide(r) fields
     * If null - get all fields
     * If string - get one field
     * If array - get array of fields
     * @param $entity - slider or slide
     * @return mixed
     * @throws ErrorException
     */
    private function getSliderOrSlide($id, $fields = null, $entity) {
        global $wpdb;

        if (!in_array($entity, array('slider', 'slide', 'preview_slide'))) {
            throw new ErrorException('Bad entity type');
        }

        if (is_null($fields)) {
            $_fields = '*';
        } elseif (is_array($fields)) {
            $_fields = implode(',', $fields);
        } else {
            $_fields = (string) $fields;
        }

        $query = sprintf(
            'SELECT %s FROM %s WHERE id=%d',
            $_fields,
            $this->mpsl_settings["{$entity}s_table"],
            $id
        );

        if (is_string($fields)) {
            $slider = $wpdb->get_var($query);
        } else {
            $slider = $wpdb->get_row($query, 'ARRAY_A');
//            foreach ($slider as &$attr) {
//                if (is_numeric($attr)) $attr = (int) $attr;
//            }
        }

        return $slider;
    }

    public function getSlider($id, $fields = null) {
        return $this->getSliderOrSlide($id, $fields, 'slider');
    }

    public function getSlide($id, $fields = null) {
        return $this->getSliderOrSlide($id, $fields, 'slide');
    }

	public function getPreviewSlide($id, $fields = null) {
		return $this->getSliderOrSlide($id, $fields, 'preview_slide');
	}

    public function isSliderExists($id) {
        $sliderId = $this->getSlider($id, 'id');
        return is_null($sliderId) ? false : true;
    }

    public function isSlideExists($id) {
        $slideId = $this->getSlide($id, 'id');
        return is_null($slideId) ? false : true;
    }

    public function getSiblings($id) {
        global $wpdb;
        return $wpdb->get_results(sprintf(
            'SELECT id FROM %s WHERE slider_id=%d ORDER BY slide_order ASC',
            $this->mpsl_settings['slides_table'],
            $id
        ), ARRAY_A);
    }
    public function getSlidesBySlider($id, $decodeFields = array()) {
        global $wpdb;
        $slides = $wpdb->get_results(sprintf(
            'SELECT * FROM %s WHERE slider_id=%d ORDER BY slide_order ASC',
            $this->mpsl_settings['slides_table'],
            $id
        ), ARRAY_A);

        foreach($slides as &$slide) {
	        foreach ($decodeFields as $field) {
		        if (isset($slide[$field])) {
			        $slide[$field] = json_decode($slide[$field], true);
		        }
	        }
        }

        return $slides;
    }

    public function updateSlidesOrder($slidesOrder){
        global $wpdb;
        $query= 'UPDATE ' . $this->mpsl_settings['slides_table'] .
                ' SET slide_order =  CASE ';
        foreach ($slidesOrder as $order => $id){
            $query .= sprintf(' WHEN id = %d THEN %d', $id, $order);
        }
        $query .= ' ELSE slide_order';
        $query .= ' END';
//        $query .= sprintf(' WHERE slider_id=%d', $sliderId);
        $wpdb->hide_errors();
        return $wpdb->query($query);
    }

}