<?php
if (!defined('ABSPATH')) exit;

class MPSLOptionsFactory {
    static $inited = false;
    static $prefix;

    public function  __construct() {
        self::$inited = true;

        global $mpsl_settings;
        self::$prefix = $mpsl_settings['prefix'];
    }

    static function createControl(&$option, $parent = '', $type = 'default') {
        ?>
        <div
            class="mpsl-option <?php echo ($type === 'style' ? 'mpsl-style-option' : '') ?> mpsl-option-<?php echo $option['type']; ?> <?php echo ($parent) ? 'mpsl-nested-option' : ''; ?>"
            data-mpsl-option-type="<?php echo $option['type']; ?>"
            data-name="<?php echo $option['name']; ?>"
            <?php echo ($parent) ? 'data-parent-name="' . $parent . '"' : ''; ?>
        >
        <?php
        switch($option['type']) {
            case 'text' : self::addLabel2($option); self::addInputText($option); break;
            case 'number' : self::addLabel2($option); self::addInputText($option); break;
            case 'textarea' : self::addLabel2($option); self::addTextarea($option); break;
            case 'select' : self::addLabel2($option);  self::addSelect($option); break;
            case 'checkbox' : self::addCheckbox($option); self::addLabel2($option); self::addBreak(); break;
            case 'radio_group' : self::addLabel2($option); self::addRadioGroup($option); break;
            case 'radio_buttons' : self::addLabel2($option); self::addRadioButtons($option); break;
            case 'button_group' : self::addLabel2($option); self::addButtonGroup($option); break;
            case 'action_group' : self::addLabel2($option); self::addActionGroup($option); break;
            case 'hidden' : self::addHidden($option); break;
            case 'library_image' : self::addLabel2($option); self::addLibraryImage($option); break;
            case 'library_video' : self::addLabel2($option); self::addLibraryVideo($option); break;
            case 'image_url' : self::addLabel2($option); self::addImageUrl($option); break;
            case 'alias' : self::addInputText($option); break;
            case 'shortcode' : self::addInputText($option); break;
            case 'align_table' : self::addAlignTable($option); break;
            case 'datepicker' : self::addDatePicker($option); break;
            case 'codemirror' : self::addLabel2($option); self::addCodeMirror($option); break;
            case 'style_editor' : self::addLabel2($option); self::addStyleEditor($option); break;
            case 'multiple' : self::addMultiple($option); break;
	        case 'font_picker' : self::addLabel2($option); self::addSelect($option); break;
	        case 'color_picker' : self::addLabel2($option); self::addColorPicker($option); break;
	        case 'text_shadow' : self::addLabel2($option); self::addTextShadow($option); break;
        }
            self::addDescription($option);
        ?>
        </div>
        <?php
    }


    static function addControl(&$option, $parent = '') {
        MPSLOptionsFactory::createControl($option, $parent);
    }
    
    static function addStyleControl(&$option, $parent = '') {
        MPSLOptionsFactory::createControl($option, $parent, 'style');
    }

    static function addLabel(&$option) {
        if (isset($option['label'])) {
    ?>
        <label for="<?php echo self::$prefix.$option['name'] ?>"><?php echo $option['label']; ?></label>
    <?php
        }
    }

    static function addBreak(){
        ?>
        <br/>
        <?php
    }

    static function addLabel2(&$option){
        if (isset($option['label2'])){
        ?>
            <label for="<?php echo self::$prefix.$option['name'] ?>"><?php echo $option['label2']; ?></label>
        <?php
        }
    }

    static function addDescription(&$option) {
        if (isset($option['description'])) {
    ?>
        <i><?php echo $option['description']; ?></i>
    <?php
        }
    }

    static function addInputText(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
    ?>
        <input type="text" id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix.$option['name']; ?>" value="<?php echo $option['value']; ?>" <?php echo $disabled . $required . $readonly; ?> />
        <?php if (isset($option['unit']) && $option['unit']) { ?>
            <span class="mpsl-option-unit"><?php echo $option['unit']; ?></span><br/>
        <?php } ?>
    <?php
    }

    static function addHidden(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        ?>
        <input type="hidden" id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix.$option['name']; ?>" value="<?php echo $option['value']; ?>" <?php echo $disabled; ?>>
        <?php
    }

    static function addTextarea(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
        $rows = (isset($option['rows']) && is_numeric($option['rows']) && $option['rows']) ? ' rows="' . $option['rows'] . '"' : '';
        $cols = (isset($option['cols']) && is_numeric($option['cols']) && $option['cols']) ? ' cols="' . $option['cols'] . '"' : '';
        $areaSize = isset($option['area_size']) && $option['area_size'] ? $option['area_size'] . '-text' : '';
    ?>
        <textarea id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix . $option['name'] ?>" <?php echo $rows . $cols . $disabled . $required . $readonly; ?> class="<?php echo $areaSize; ?>"><?php echo $option['value']; ?></textarea>
    <?php
    }
    
    static function addCodeMirror(&$option){        
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
        ?>
        <textarea id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix . $option['name'] ?>" <?php echo $disabled . $required . $readonly; ?> ><?php echo $option['value']; ?></textarea>
        <?php
    }

    static function addSelect(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
    ?>
        <select id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix . $option['name']; ?>"<?php echo $disabled . $required . $readonly; ?>>
            <?php if (isset( $option['list'] )) {
	            $attrSettings = isset($option['listAttrSettings']) ? $option['listAttrSettings'] : array();
                foreach($option['list'] as $key => $value) {
                    $selected = isset($option['value']) && $option['value'] === $key ? ' selected="selected"' : '';
	                if (is_array($value)) {
		                $attrs = '';
		                if (isset($value['attrs']) && is_array($value['attrs'])) {
							foreach ($value['attrs'] as $attrName => $attrVal) {
								$attrStr = '';
								switch ($attrSettings[$attrName]['type']) {
									case 'split':
										$attrStr .= implode($attrSettings[$attrName]['delimiter'], $attrVal);
										break;
									case 'json':
										$attrStr .= htmlspecialchars(json_encode($attrVal), ENT_QUOTES, 'UTF-8');
										break;
								}
								$attrs .= "$attrName='$attrStr' ";
							}
		                }
	                ?>
						<option value="<?php echo $key; ?>" <?php echo $attrs . ' ' . $selected; ?>><?php echo $value['label']; ?></option>
	                <?php } else { ?>
		                <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
	               <?php }
                }
            }?>
        </select>
    <?php
    }

    static function addCheckbox(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';

        if (array_key_exists('list', $option)) {
            foreach ($option['list'] as $key => $opt) {
                $checked = (isset($option['value']) && in_array($key, $option['value'])) ? ' checked="checked"' : '';
        ?>
                <input type="checkbox" id="<?php echo self::$prefix.$option['name'] . $key; ?>" name="<?php echo self::$prefix . $option['name']; ?>" value="<?php echo $key;?>"<?php echo $checked . $disabled . $required . $readonly; ?> />
                <label for="<?php echo self::$prefix.$option['name'] . $key; ?>"><?php echo $opt; ?></label>
        <?php
            }

        } else {
            $checked = isset($option['value']) && $option['value'] === true ? ' checked="checked"' : '';
        ?>
            <input type="checkbox" id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix . $option['name']; ?>"<?php echo $checked . $disabled . $required . $readonly; ?> />
    <?php
        }
    }

    static function addRadioGroup(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
        foreach($option['list'] as $key => $opt){
            $checked = isset($option['value']) && $option['value'] === $key ? ' checked="checked"' : '';
        ?>
            <input id="<?php echo self::$prefix.$option['name'] . $key; ?>" type="radio" name="<?php echo self::$prefix . $option['name']; ?>" value="<?php echo $key;?>"<?php echo $checked . $disabled . $required . $readonly; ?> /><label for="<?php echo self::$prefix.$option['name'] . $key; ?>"><?php echo $opt; ?></label>
        <?php
        }
    }

    static function addRadioButtons(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
        foreach($option['list'] as $key => $opt){
            if (isset($option['value']) && $option['value'] === $key) {
                $checked = ' checked="checked"';
                $checkedClass = ' button-primary';
            } else {
                $checked = '';
                $checkedClass = '';
            }
//            $checked = isset($option['value']) && $option['value'] === $key ? ' checked="checked"' : '';
        ?>
            <input id="<?php echo self::$prefix.$option['name'] . $key; ?>" type="radio" name="<?php echo self::$prefix . $option['name']; ?>" value="<?php echo $key;?>"<?php echo $checked . $disabled . $required . $readonly; ?> /><label for="<?php echo self::$prefix.$option['name'] . $key; ?>" class="button-secondary <?php echo $checkedClass;?>"><?php echo $opt; ?></label>
        <?php
        }
    }

    static function addButtonGroup(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
        $buttonSize = isset($option['button_size']) && $option['button_size'] ? $option['button_size'] : 'large'; // small, large, hero

        echo '<div class="button-group button-' . $buttonSize . '">';
        foreach($option['list'] as $key => $opt){
            $active = (isset($option['value']) && $option['value'] === $key) ? 'active' : '';
            ?>
            <button class="button <?php echo $active; ?>" value="<?php echo $key;?>" id="<?php echo self::$prefix.$option['name'] . $key; ?>" name="<?php echo self::$prefix . $option['name']; ?>" <?php echo $disabled . $required . $readonly; ?>><?php echo $opt; ?></button>
        <?php
        }
        echo '</div>';
    }

    static function addLibraryImage(&$option) {
        $can_remove = isset($option['can_remove']) && $option['can_remove'] ? true : false;
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $buttonLabel = isset($option['button_label']) ? $option['button_label'] : __('Select Image', MPSL_TEXTDOMAIN);
        $id = (int) trim($option['value']);
        ?>
        <input type="hidden" value="<?php echo $id; ?>" id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix . $option['name']; ?>" <?php echo $disabled . $required; ?>>
        <button class="mpsl-option-library-image-btn button-secondary"><?php echo $buttonLabel;?></button>
        <?php
        if ($can_remove) {
        ?>
          <a href="#" class="mpsl-option-library-image-remove"><?php _e('remove', MPSL_TEXTDOMAIN); ?></a>
        <?php
        }
    }

    static function addLibraryVideo(&$option){
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $buttonLabel = isset($option['button_label']) ? $option['button_label'] : __('Select Image', MPSL_TEXTDOMAIN);
        $id = (int) trim($option['value']);
        ?>
        <input type="hidden" value="<?php echo $id; ?>" id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix . $option['name']; ?>" <?php echo $disabled . $required; ?>>
        <button class="mpsl-option-library-video-btn button-secondary"><?php echo $buttonLabel;?></button>
        <?php
    }

    static function addImageUrl(&$option){
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
        $buttonLabel = isset($option['button_label']) ? $option['button_label'] : __('Load', MPSL_TEXTDOMAIN);
        ?>
        <input type="text" value="<?php echo $option['value']; ?>" id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix . $option['name']; ?>" <?php echo $disabled . $required; ?>>
        <button class="mpsl-option-image-load-btn button-secondary"><?php echo $buttonLabel;?></button>
        <?php
    }

    static function addAlignTable(&$option) {
    ?>
        <table class="mpsl-align-table">
            <tbody>
                <tr>
                    <td><a href="javascript:void(0)" data-hor="left" data-vert="top"></a></td>
                    <td><a href="javascript:void(0)" data-hor="center" data-vert="top"></a></td>
                    <td><a href="javascript:void(0)" data-hor="right" data-vert="top"></a></td>
                </tr>
                <tr>
                    <td><a href="javascript:void(0)" data-hor="left" data-vert="middle"></a></td>
                    <td><a href="javascript:void(0)" data-hor="center" data-vert="middle"></a></td>
                    <td><a href="javascript:void(0)" data-hor="right" data-vert="middle"></a></td>
                </tr>
                <tr>
                    <td><a href="javascript:void(0)" data-hor="left" data-vert="bottom"></a></td>
                    <td><a href="javascript:void(0)" data-hor="center" data-vert="bottom"></a></td>
                    <td><a href="javascript:void(0)" data-hor="right" data-vert="bottom"></a></td>
                </tr>
            </tbody>
        </table>

        <?php
            self::addControl($option['options']['hor_align'], $option['name']);
            self::addControl($option['options']['vert_align'], $option['name']);
            self::addControl($option['options']['offset_x'], $option['name']);
            self::addControl($option['options']['offset_y'], $option['name']);
        ?>
    <?php
    }

    static function addDatePicker(&$option){
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
    ?>
        <input type="text" value="<?php echo $option['value']; ?>" id="<?php echo self::$prefix.$option['name']; ?>" name="<?php echo self::$prefix . $option['name']; ?>" <?php echo $disabled . $required; ?>>
    <?php
    }

    static function addActionGroup(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
        $count = 0;

        echo '<div class="actions">';
        foreach ($option['list'] as $key => $opt) { ?>
            <a href="javascript:void(0)" value="<?php echo $key;?>" <?php echo $disabled . $required . $readonly; ?> ><?php echo $opt; ?></a>
        <?php
            $count++;
            if ($count < count($option['list'])) echo ' | ';
        }
        echo '</div>';
    }

    static function addSeparator(){
        echo '<hr/>';
    }
    
    static function addStyleEditor(&$option) {
	    $_disabled = isset($option['disabled']) && $option['disabled'];
	    $disabledAttr = ' disabled="disabled"';
//        $disabled = $_disabled ? $disabledAttr : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';

	    $editBtnDisabled = $_disabled ? $disabledAttr : '';
	    
	    $listDisabled = $_disabled ? $disabledAttr : '';
	    $removeBtnDisabled = $_disabled ? $disabledAttr : '';
        ?>
	    <select class="mpsl-layer-style-list" <?php echo $listDisabled . $required . $readonly; ?>>
			<optgroup label="<?php _e('Custom', MPSL_TEXTDOMAIN); ?>" class="mpsl-layer-style-list-group-custom"></optgroup>
			<optgroup label="<?php _e('Default', MPSL_TEXTDOMAIN); ?>" class="mpsl-layer-style-list-group-default"></optgroup>
	    </select>
        <button class="button button-primary mpsl-edit-layer-style" <?php echo $editBtnDisabled . $required . $readonly; ?>><?php echo $option['edit_label'] ?></button>
        <button class="button mpsl-remove-layer-style" <?php echo $removeBtnDisabled . $required . $readonly; ?>><?php echo $option['remove_label'] ?></button>
	    <input type="hidden" class="mpsl-layer-style-value " id="<?php echo self::$prefix.$option['name'] ?>" name="<?php echo self::$prefix . $option['name']; ?>" value="<?php echo $option['value']; ?>" disabled="disabled" />
        <?php
    }

    static function addMultiple(&$option) {
	    ?><textarea id="<?php echo self::$prefix.$option['name'] ?>" name="<?php echo self::$prefix . $option['name']; ?>"><?php echo is_string($option['value']) ? $option['value'] : json_encode($option['value']); ?></textarea><?php
    }

	static function addColorPicker(&$option) {
        $disabled = isset($option['disabled']) && $option['disabled'] ? ' disabled="disabled"' : '';
        $required = isset($option['required']) && $option['required'] ? ' required="required"' : '';
        $readonly = isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '';
        ?>
	    <input type="text" class="mpsl-color-picker" value="<?php echo $option['value']; ?>" id="<?php echo self::$prefix.$option['name'] ?>" name="<?php echo self::$prefix . $option['name']; ?>" <?php echo $disabled . $required . $readonly; ?> />
        <?php
    }

	static function addTextShadow(&$option) {
	?>
		<table class="form-table">
            <tbody>
                <?php foreach ($option['options'] as $optionName => $opt) { ?>
                    <tr>
                        <?php if (isset($opt['label'])) { ?>
                            <th>
                                <?php self::addLabel($opt); ?>
                            </th>
                            <td>
                                <?php self::addControl($opt, $option['name']); ?>
                            </td>
                        <?php } else { ?>
                            <th colspan="2" class="th-full">
                                <?php self::addControl($opt, $option['name']); ?>
                            </th>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
	<?php
    }

}

if (!MPSLOptionsFactory::$inited) {
    new MPSLOptionsFactory();
}
