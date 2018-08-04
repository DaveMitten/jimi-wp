<?php if (!defined('ABSPATH')) exit; ?>
<h3><?php _e('Slides List: ', MPSL_TEXTDOMAIN);?></h3>
<?php if (!empty($slides)) { ?>
    <table class="table widefat mpsl-slides-table">
        <col width="20">
        <thead>
            <tr>
                <th><?php // _e('Order', MPSL_TEXTDOMAIN);?></th>
                <th><?php _e('ID', MPSL_TEXTDOMAIN); ?></th>
                <th><?php _e('Title', MPSL_TEXTDOMAIN); ?></th>
                <th><?php _e('Status', MPSL_TEXTDOMAIN); ?></th>
                <th><?php _e('Visible for', MPSL_TEXTDOMAIN)?></th>
                <th><?php _e('Date From', MPSL_TEXTDOMAIN); ?></th>
                <th><?php _e('Date Until', MPSL_TEXTDOMAIN); ?></th>
                <th><?php _e('Action', MPSL_TEXTDOMAIN); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $menuUrl = menu_page_url($mpsl_settings['plugin_name'], false);
                foreach($slides as $slide) {
                    $slideEditUrl = add_query_arg(array('view' => 'slide','id' => $slide['id']), $menuUrl);
                    $slideDuplicateUrl = add_query_arg(array('view' => 'slide','id' => $slide['id']), $menuUrl);
                    $slideDeleteUrl = add_query_arg(array('view' => 'slide','id' => $slide['id']), $menuUrl);
                    ?>
                    <tr data-id="<?php echo $slide['id'] ?>">
                        <td class="mpsl-slide-sort-handle">
	                        <div class="mpsl-slide-sort-icon"></div>
                        </td>
                        <td><?php echo $slide['id']; ?></td>
                        <td><a class="mpsl-slide-name" href="<?php echo $slideEditUrl; ?>"><?php echo ($slide['title']) ? $slide['title'] : '<i>' . __('not set', MPSL_TEXTDOMAIN) . '</i>';?></a></td>
                        <td><?php echo (isset($slide['options']['status']) && $slide['options']['status']) ? $slide['options']['status'] : 'published' ; ?></td>
                        <td><?php echo (isset($slide['options']['need_logged_in']) && $slide['options']['need_logged_in']) ? 'logged-in' : 'all'; ?></td>
                        <td><?php echo (empty($slide['options']['date_from'])) ? '-' : $slide['options']['date_from']; ?></td>
                        <td><?php echo (empty($slide['options']['date_until'])) ? '-' : $slide['options']['date_until']; ?></td>
                        <td>
                            <a href="<?php echo $slideEditUrl; ?>" class="button-secondary"><?php _e('Edit', MPSL_TEXTDOMAIN); ?></a>
                            <button type="button" class="mpsl_duplicate_slide button-secondary" data-id="<?php echo $slide['id'] ?>"><?php _e('Duplicate', MPSL_TEXTDOMAIN); ?></button>
                            <button type="button" class="mpsl_delete_slide button-secondary" data-id="<?php echo $slide['id'] ?>"><?php _e('Delete', MPSL_TEXTDOMAIN); ?></button>
                        </td>
                    </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
<?php }?>
<div class="control-panel">
    <?php
    $menuUrl = menu_page_url($mpsl_settings['plugin_name'], false);
    $sliderSettingsPageUrl = add_query_arg(array('view'=>'slider', 'id'=> $this->getSliderId()), $menuUrl);
    ?>
    <button type="button" id="create_slide" class="button-primary mpsl-button" data-slider-id="<?php echo $_GET['id'] ?>"><?php _e('New Slide', MPSL_TEXTDOMAIN); ?></button>
    <a id="slider_settings" class="button-secondary mpsl-button" href="<?php echo $sliderSettingsPageUrl;?>"><?php _e('Slider Settings', MPSL_TEXTDOMAIN); ?></a>
    <a class="button-secondary mpsl-button" href="<?php echo $menuUrl ?>"><?php _e('Close', MPSL_TEXTDOMAIN); ?></a>
</div>