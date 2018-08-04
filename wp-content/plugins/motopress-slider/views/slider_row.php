<?php
if (!defined('ABSPATH')) exit;

global $mpsl_settings;
$menuUrl = admin_url( "admin.php?page=".$mpsl_settings['plugin_name']);
$sliderEditUrl = add_query_arg(array('view' => 'slider','id' => $slider['id']), $menuUrl);
$slidesEditUrl = add_query_arg(array('view' => 'slides', 'id' => $slider['id']), $menuUrl);

$sliderPreviewUrl = add_query_arg(array('view' => 'preview', 'id'=> $slider['id'], $menuUrl));

$visibleFrom = empty($slider['options']['visible_from']) ? '-' : $slider['options']['visible_from'] . 'px';
$visibleTill = empty($slider['options']['visible_till']) ? '-' : $slider['options']['visible_till'] . 'px';
?>
<tr>
    <td><?php echo $slider['id']; ?></td>
    <td><a class="mpsl-slider-name" href="<?php echo $slidesEditUrl; ?>"><?php echo $slider['title'] . ' (' . $slider['alias'] . ')';?></a></td>
    <td><?php echo '[' . $mpsl_settings['shortcode_name'] . ' ' . $slider['alias'] . ']'; ?></td>
    <td><?php echo $visibleFrom . ' / ' . $visibleTill; ?></td>
    <td class="btn-group" role="group">
        <a class="button-secondary" href="<?php echo $sliderEditUrl; ?>"><?php _e('Settings', MPSL_TEXTDOMAIN);?></a>
        <a class="button-primary" href="<?php echo $slidesEditUrl?>"><?php _e('Edit Slides', MPSL_TEXTDOMAIN);?></a>
        <a class="mpsl-preview-slider-btn button-secondary" data-mpsl-slider-id="<?php echo $slider['id']; ?>"  href="#"><?php _e('Preview', MPSL_TEXTDOMAIN);?></a>
        <a class="mpsl-duplicate-slider-btn button-secondary" data-mpsl-slider-id="<?php echo $slider['id']; ?>" href=""><?php _e('Duplicate', MPSL_TEXTDOMAIN);?></a>
        <a class="mpsl-delete-slider-btn button-secondary" data-mpsl-slider-id="<?php echo $slider['id']; ?>"><?php _e('Delete', MPSL_TEXTDOMAIN);?></a>
    </td>
</tr>