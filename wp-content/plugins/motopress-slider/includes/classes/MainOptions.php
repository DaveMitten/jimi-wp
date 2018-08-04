<?php

include_once dirname(__FILE__) . '/MPSLOptions.php';

abstract class MPSLMainOptions extends MPSLOptions {
    const SLIDERS_TABLE = 'mpsl_sliders';
    const SLIDES_TABLE = 'mpsl_slides';
    const SLIDES_PREVIEW_TABLE = 'mpsl_slides_preview';
    const SLIDERS_PREVIEW_TABLE = 'mpsl_sliders_preview';

    protected $id = null;
//    protected $optionValues = array();
//    protected $options = null;

    public function __construct() {
        parent::__construct();
    }

    abstract public function getAttributes();

    protected function updateOption($group, $name, $value) {
        if (array_key_exists($name, $this->options[$group]['options'])) {
            $this->options[$group]['options'][$name]['value'] = $value;
        }
    }

    abstract protected function load($id);

    /**
     * @param array | null $options - Options
     * @param bool $isGrouped - Is data grouped ?
     */
    public function overrideOptions($options = null, $isGrouped = true) {
        $existingOptions = is_null($options) ? null : $options;
        foreach ($this->options as $grpName => $grp) {
            foreach ($grp['options'] as $optName => $opt) {
                if (is_null($existingOptions)) {
                    $this->updateOption($grpName, $optName, $opt['default']);
                } else {
                    if ($isGrouped and array_key_exists($optName, $existingOptions[$grpName])) {
                        $this->updateOption($grpName, $optName, $existingOptions[$grpName][$optName]);
                    } elseif (!$isGrouped and array_key_exists($optName, $existingOptions)) {
                        $this->updateOption($grpName, $optName, $existingOptions[$optName]);
                    } else {
                        $this->updateOption($grpName, $optName, $opt['default']);
                    }
                }
            }
        }

//	    $this->extractOptionValues();
    }

    public function getGroupAttr($name, $attr) {
        if (array_key_exists($attr, $this->options[$name])) {
            return $this->options[$name][$attr];
        } else {
            return false;
        }
    }

    public function &getOption($group, $name) {
        if (array_key_exists($name, $this->options[$group]['options'])) {
            return $this->options[$group]['options'][$name];
        } else {
            return false;
        }
    }

    public function getOptionAttr($group, $name, $attr) {
        if (array_key_exists($name, $this->options[$group]['options']) and array_key_exists($attr, $this->options[$group]['options'][$name])) {
            return $this->options[$group]['options'][$name][$attr];
        } else {
            return false;
        }
    }

    /*public function extractOptionValues() {
        $options = array();
        foreach ($this->options as $grpName => $grp) {
            foreach ($grp['options'] as $optName => $opt) {                                                                                
                $options[$optName] = $opt['value'];
            }
        }
        $this->optionValues = $options;
    }

	public function getOptionValues() {
		return $this->optionValues;
	}*/

	public function getOptionValues() {
        $options = array();
        foreach ($this->options as $grpName => $grp) {
            foreach ($grp['options'] as $optName => $opt) {
                $options[$optName] = $opt['value'];
            }
        }
        return $options;
    }
    
    function getOptionValuesForExport(&$internalResources = array()){
        $options = array();
        foreach ($this->options as $grpName => $grp) {
            foreach ($grp['options'] as $optName => $opt) {                                                                
                if ( $opt['type'] === 'library_image' && !empty($opt['value']) ){
                    if (!isset($internalResources[$opt['value']])) {
                        $internalResources[$opt['value']] = array();
                        $internalResources[$opt['value']]['value'] = wp_get_attachment_url($opt['value']);
                    }
                    $options[$optName] = array(
                        'need_update' => true,
                        'old_value' => $opt['value']                        
                    );
                } else {
                    $options[$optName] = $opt['value'];
                }
            }
        }
        return $options;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

}