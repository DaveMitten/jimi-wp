<?php

include_once dirname(__FILE__) . '/OptionsFactory.php';

abstract class MPSLOptions {
    protected $pluginDir;
    protected $options = null;

    public function __construct() {
        global $mpsl_settings;
        $this->pluginDir = $mpsl_settings['plugin_dir_path'];
    }

    abstract public function render();

    /**
     * Prepare settings before using
     */
	protected function prepareOptions(&$options = array()) {
        foreach ($options as $grpName => $grp) {
            foreach ($grp['options'] as $optName => $opt) {
                $options[$grpName]['options'][$optName]['isChild'] = false;
                $options[$grpName]['options'][$optName]['group'] = $grpName;
                $options[$grpName]['options'][$optName]['name'] = $optName;
	            $options[$grpName]['options'][$optName]['unit'] = array_key_exists('unit', $opt) ? $opt['unit'] : '';
	            $options[$grpName]['options'][$optName]['value'] = array_key_exists('default', $opt) ? $opt['default'] : '';

                if (array_key_exists('options', $opt)) {
                    foreach ($options[$grpName]['options'][$optName]['options'] as $childOptName => $childOpt) {
                        $options[$grpName]['options'][$optName]['options'][$childOptName]['isChild'] = true;
                        $options[$grpName]['options'][$optName]['options'][$childOptName]['group'] = $grpName;
                        $options[$grpName]['options'][$optName]['options'][$childOptName]['name'] = $childOptName;
	                    $options[$grpName]['options'][$optName]['options'][$childOptName]['unit'] = array_key_exists('unit', $childOpt) ? $childOpt['unit'] : '';
                        $options[$grpName]['options'][$optName]['options'][$childOptName]['value'] = $childOpt['default'];
	                    if (!array_key_exists('dependency', $childOpt) && array_key_exists('dependency', $opt)) {
		                    $options[$grpName]['options'][$optName]['options'][$childOptName]['dependency'] = $opt['dependency'];
	                    }
	                    if (!array_key_exists('disabled_dependency', $childOpt) && array_key_exists('disabled_dependency', $opt)) {
		                    $options[$grpName]['options'][$optName]['options'][$childOptName]['disabled_dependency'] = $opt['disabled_dependency'];
	                    }
                    }
                }
            }
        }
    }

	public function getOptions($grouped = false) {
	    if ($grouped) {
			return $this->options;
		} else {
			$options = array();
			foreach ($this->options as $grp) {
				$options = array_merge($options, $grp['options']);

				foreach ($grp['options'] as $name => $opt) {
					if (array_key_exists('options', $opt)) {
						$options = array_merge($options, $opt['options']);
					}
				}
			}
			return $options;
		}

    }

	protected function getDefaults(&$options = array()){
        $defaults = array();
        foreach($options as $grp){
            foreach($grp['options'] as $optName => $opt) {
	            $defaults[$optName] = array_key_exists('default', $opt) ? $opt['default'] : '';
            }
        }
        return $defaults;
    }

	public function getOptionsDefaults($settingsFileName = false) {
        $options = include($this->getSettingsPath($settingsFileName));
        $defaults = array();

        foreach($options as $grp) {
            if (isset($grp['options'])) {
                foreach ($grp['options'] as $optName => $opt){
	                $defaults[$optName] = array_key_exists('default', $opt) ? $opt['default'] : '';
                    if (array_key_exists('options', $opt)) {
                        foreach ($opt['options'] as $childOptName => $childOpt) {
                            $defaults[$childOptName] = $childOpt['default'];
                        }
                    }
                }
            }
        }
        return $defaults;
    }

	abstract protected function getSettingsFileName();
	abstract protected function getViewFileName();

	protected function getSettingsPath($settingsFileName = false) {
		return $this->pluginDir . 'settings/' . ($settingsFileName ? $settingsFileName : $this->getSettingsFileName()) . '.php';
	}
	protected function getViewPath($viewFileName = false) {
		return $this->pluginDir . 'views/' . ($viewFileName ? $viewFileName : $this->getViewFileName()) . '.php';
	}

}