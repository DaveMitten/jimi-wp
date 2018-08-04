<?php
if (!defined('ABSPATH')) exit;

$id = $slider->getId();?>
<div class="mpsl-slider-settings-wrapper">
<?php
if (is_null($id)){
    echo '<h3>' . __('New Slider Settings', MPSL_TEXTDOMAIN) . '</h3>';
} else {
    echo '<h3>' . __('Slider Settings', MPSL_TEXTDOMAIN) . '</h3>';
}?>

<div id="mpsl-slider-settings-tabs" class="mpsl-slider-settings-wrapper mpsl_options_wrapper">
    <?php $sliderSettingsPrefix = 'mpsl-slider-settings-'; ?>
    <ul>
    <?php foreach ($slider->options as $groupKey => $group) {
        echo '<li><a href="#' . $sliderSettingsPrefix . $groupKey . '">' . $group['title'] . '</a></li>';
    } ?>
    </ul>
    <?php foreach ($slider->options as $groupKey => $group) { ?>
    <div id="<?php echo $sliderSettingsPrefix . $groupKey; ?>">
        <table class="form-table">
            <tbody>
            <?php foreach ($group['options'] as $optionKey => $option) { ?>
                <tr class="mpsl-option-wrapper <?php echo ($option['type'] === 'hidden') ? 'mpsl-option-wrapper-hidden' : ''; ?>">
                <?php if (isset($option['label'])) { ?>
                    <th>
                        <?php MPSLOptionsFactory::addLabel($option); ?>
                    </th>
                    <td data-group="<?php echo $groupKey; ?>">
                        <?php MPSLOptionsFactory::addControl($option); ?>
                    </td>
                <?php } else { ?>
                    <th data-group="<?php echo $groupKey; ?>" colspan="2" class="th-full">
                        <?php MPSLOptionsFactory::addControl($option); ?>
                    </th>
                <?php } ?>
                </tr>
            <?php } ?>
            <tbody>
        </table>
    </div>
    <?php } ?>
</div>

<div class="control-panel">
    <?php if (is_null($slider->getId())) {
        echo '<button type="button" class="button-primary mpsl-button" id="create_slider">' . __('Create Slider', MPSL_TEXTDOMAIN) . '</button>';
        echo '<a class="button-secondary mpsl-button" href="' . add_query_arg(array('view' => 'sliders') ,menu_page_url($mpsl_settings['plugin_name'], false)) . '">' . __('Cancel', MPSL_TEXTDOMAIN) . '</a>';
    } else {
        echo '<button data-id="' . $slider->getId() . '" type="button" class="button-primary mpsl-button" id="update_slider">' . __('Save Settings', MPSL_TEXTDOMAIN) . '</button>';
//        echo '<button data-id="' . $slider->getId() . '" type="button" class="button-secondary mpsl-button" id="delete_slider">' . __('Delete Slider', MPSL_TEXTDOMAIN) . '</button>';
        echo '<a id="edit_slides" class="button-secondary mpsl-button" href="' . add_query_arg(array('view' => 'slides', 'id' => $slider->getId()), menu_page_url($mpsl_settings['plugin_name'], false)) . '">' . __('Edit Slides', MPSL_TEXTDOMAIN) . '</a>';
	    echo '<a id="slider_preview" class="button-secondary mpsl-button" href="#" data-mpsl-slider-id="'. $slider->getId() .'" >' . __('Preview slider', MPSL_TEXTDOMAIN) . '</a>';
        echo '<a class="button-secondary mpsl-button" href="' . add_query_arg(array('view' => 'sliders') ,menu_page_url($mpsl_settings['plugin_name'], false)) . '">' . __('Close', MPSL_TEXTDOMAIN) . '</a>';
    }
    ?>
</div>
</div>
<?php include $mpsl_settings['plugin_dir_path'] . 'views/preview-dialog.php'; ?>