<?php
if (!defined('ABSPATH')) exit;

global $mpsl_settings;
$mpClasses = '';
if (!empty($mpAtts) and (is_plugin_active('motopress-content-editor/motopress-content-editor.php') || is_plugin_active('motopress-content-editor-lite/motopress-content-editor.php'))) {
    $mpClasses = MPCEShortcode::getBasicClasses('mpsl') . MPCEShortcode::getMarginClasses($mpAtts['margin']) . $mpAtts['mp_style_classes'];
}

if ($edit_mode) {
	$layerPresets = MPSLLayerPresetOptions::getInstance();
}

// $sliderOptions
// $sliderOptions['options'] -- slider options
// $sliderOptions['slides'] -- array of slides
// $sliderOptions['slides'][%id%]['options'] -- slide options
// $sliderOptions['slides'][%id%]['layers'] -- array of layers
// $sliderOptions['slides'][%id%]['layers'][%id%] -- layer options
$sliderSettingsDataAttrs = '';
$sliderSettingsDataAttrs .= ' data-full-window-width="' . (($sliderOptions['options']['full_width'] and !$edit_mode) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-timer="' . (($sliderOptions['options']['enable_timer'] and count($sliderOptions['slides']) > 1 and !MPSLSharing::$isMPCEEditor) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-timer-delay="' . $sliderOptions['options']['slider_delay'] . '"';
$sliderSettingsDataAttrs .= ' data-hover-timer="' . (($sliderOptions['options']['hover_timer']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-counter="' . (($sliderOptions['options']['counter'] and !$edit_mode) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-slider-layout="auto"';
$sliderSettingsDataAttrs .= ' data-grid-width="' . $sliderOptions['options']['width'] . '"';
$sliderSettingsDataAttrs .= ' data-grid-height="' . $sliderOptions['options']['height'] . '"';
$sliderSettingsDataAttrs .= ' data-timer-reverse="' . (($sliderOptions['options']['timer_reverse']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-arrows-show="' . (($sliderOptions['options']['arrows_show']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-thumbnails-show="' . (($sliderOptions['options']['thumbnails_show']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-slideshow-timer-show="' . (($sliderOptions['options']['slideshow_timer_show']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-slideshow-ppb-show="' . (($sliderOptions['options']['slideshow_ppb_show']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-controls-hide-on-leave="' . (($sliderOptions['options']['controls_hide_on_leave']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-swipe="' . (($sliderOptions['options']['swipe']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-delay-init="' . (($sliderOptions['options']['delay_init']) ? $sliderOptions['options']['delay_init'] : 0) . '"';
$sliderSettingsDataAttrs .= ' data-scroll-init="' . (($sliderOptions['options']['scroll_init']) ? 'true' : 'false') . '"';
$sliderSettingsDataAttrs .= ' data-start-slide="' . (($sliderOptions['options']['start_slide'] ) ? $sliderOptions['options']['start_slide']  : 1) . '"';

if (!MPSLSharing::$isMPCEEditor) {
    $sliderSettingsDataAttrs .= ' data-visible-from="' . $sliderOptions['options']['visible_from'] . '"';
    $sliderSettingsDataAttrs .= ' data-visible-till="' . $sliderOptions['options']['visible_till'] . '"';
}
$sliderSettingsDataAttrs .= ' data-custom-class="' . trim($sliderOptions['options']['custom_class']) . '"';
//    $sliderSettingsDataAttrs .= ' data-min-height="' . $sliderOptions['options']['min_height'] . '"'; // Not using
//	$sliderSettingsDataAttrs .= sprintf(' data-ppb-location="%s"', apply_filters('mpsl_settings_slider_ppb_location', 'arrows')); // TODO: Wiki | arrows/pagination
$sliderSettingsDataAttrs .= ($edit_mode) ? ' data-edit-mode="true"' : '';

$aspect = $sliderOptions['options']['height'] / $sliderOptions['options']['width'];
$sliderWrapperId = 'motoslider_wrapper' . uniqid();
$sliderCustomStyles = trim($sliderOptions['options']['custom_styles']);
if (!empty($sliderCustomStyles)) {
    echo '<style type="text/css">' . $sliderCustomStyles . '</style>';
}

$emPresets = array();
$emPrivatePresets = array();
$sliderFonts = array();
?>

<div class="motoslider_wrapper <?php echo $mpClasses; ?>" id="<?php echo $sliderWrapperId;?>" <?php
    if ($edit_mode) {
        echo 'style="width:' . ($sliderOptions['options']['width']) . 'px"';
    }
?>>
    <div data-motoslider style="height: <?php echo $sliderOptions['options']['height'] . 'px'; ?>; max-height: <?php echo $sliderOptions['options']['height'] . 'px'; ?>;"></div>
    <div class="motoslider">
        <div id="settings" <?php echo $sliderSettingsDataAttrs; ?>>
        </div>
        <div id="slides">
            <?php foreach($sliderOptions['slides'] as $slide){
                $slideOptions = $slide['options'];
                $slideDataAttrs = '';
	            $hasVisibleSlides = true;

                /*$slideVisible = true;

                $isPublished = isset($slideOptions['status']) && $slideOptions['status'] === 'published';

                $isNeedLogin = isset($slideOptions['need_logged_in']) && $slideOptions['need_logged_in'];
                $canCurrentUserView = $isNeedLogin ? is_user_logged_in() : true;
//
                $isCurDateInVisiblePeriod = true;

                if (isset($slideOptions['date_from']) && $slideOptions['date_from'] !== '') {
                    $dateFrom = strtotime($slideOptions['date_from']);
                    if (false !== $dateFrom && -1 !== $dateFrom && current_time('timestamp') < $dateFrom) {
                        $isCurDateInVisiblePeriod = false;
                    }
                }
                if (isset($slideOptions['date_until']) && $slideOptions['date_until'] !== '') {
                    $dateUntil = strtotime($slideOptions['date_until']);
                    if (false !== $dateUntil && -1 !== $dateUntil && current_time('timestamp') > $dateUntil) {
                        $isCurDateInVisiblePeriod = false;
                    }
                }

                $slideVisible = $edit_mode || ($isPublished && $canCurrentUserView && $isCurDateInVisiblePeriod);
                if ($slideVisible) {
                    $hasVisibleSlides = true;
                } else {
                    continue;
                }*/

	            $sliderFonts = array_merge_recursive($sliderFonts, $slideOptions['fonts']);

                $slideDataAttrs .= isset($slideOptions['slide_classes']) && $slideOptions !== '' ? ' data-class="' . $slideOptions['slide_classes'] . '"' : '';
                $slideDataAttrs .= isset($slideOptions['slide_id']) && $slideOptions !== '' ? ' data-id="' . $slideOptions['slide_id'] . '"' : '';
                $slideDataAttrs .= isset($sliderOptions['options']['slider_animation']) && ($sliderOptions['options']['slider_animation'] !== '') ? ' data-animation="' . $sliderOptions['options']['slider_animation'] . '"' : '';
                $slideDataAttrs .= isset($sliderOptions['options']['slider_animation']) && ($sliderOptions['options']['slider_animation'] !== '') ? ' data-fade-animation="' . $sliderOptions['options']['slider_animation'] . '"' : '';
                $slideDataAttrs .= isset($sliderOptions['options']['slider_duration']) && ($sliderOptions['options']['slider_duration'] !== '') ? ' data-duration="' . $sliderOptions['options']['slider_duration'] . '"' : '';
                $slideDataAttrs .= isset($sliderOptions['options']['slider_easing']) && ($sliderOptions['options']['slider_easing'] !== '') ? ' data-easing="' . $sliderOptions['options']['slider_easing'] . '"' : '';

                if (!$edit_mode && isset($slideOptions['link']) && $slideOptions['link'] !== ''){
                    $slideDataAttrs .= ' data-link="' . $slideOptions['link'] . '"';
                    $target = isset($slideOptions['link_target']) && $slideOptions['link_target'] === true ? '_blank' : '_self';
                    $slideDataAttrs .= ' data-link-target="' . $target . '"';
                    if (isset($slideOptions['link_id']) && $slideOptions['link_id'] !== '') {
                        $slideDataAttrs .= ' data-link-id="' . $slideOptions['link_id'] . '"';
                    }
                    if (isset($slideOptions['link_class']) && $slideOptions['link_class'] !== '') {
                        $slideDataAttrs .= ' data-link-class="' . $slideOptions['link_class'] . '"';
                    }
                    if (isset($slideOptions['link_rel']) && $slideOptions['link_rel'] !== '') {
                        $slideDataAttrs .= ' data-link-rel="' . $slideOptions['link_rel'] . '"';
                    }if (isset($slideOptions['link_title']) && $slideOptions['link_title'] !== '') {
                        $slideDataAttrs .= ' data-link-title="' . $slideOptions['link_title'] . '"';
                    }
                }

                $slideBGContainerDataAttrs = ''; // animations
                ?>
                <div class="slide" <?php echo $slideDataAttrs; ?>>
                    <div class="slide_bg" <?php echo $slideBGContainerDataAttrs; ?>>
                        <?php
                        // Background Color
                        if (isset($slideOptions['bg_color_type'])) {
                            // Background Color Single
                            if ($slideOptions['bg_color_type'] === 'color' && $slideOptions['bg_color'] !== '') {
                                $slideBGDataAttrs = ' data-type="' . $slideOptions['bg_color_type'] . '"';
                                $slideBGDataAttrs .= ' data-color="' . $slideOptions['bg_color'] . '"';
                                echo '<div ' . $slideBGDataAttrs . '></div>';
                            }
                            // Background Gradient
                            if ($slideOptions['bg_color_type'] === 'gradient' && ($slideOptions['bg_grad_color_1'] !== '' || $slideOptions['bg_grad_color_2'] !== '')) {
                                $slideBGDataAttrs = ' data-type="' . $slideOptions['bg_color_type'] . '"';
//                                if ($slideOptions['bg_grad_color_1'] !== '') {
                                    $slideBGDataAttrs .= ' data-color-initial="' . ($slideOptions['bg_grad_color_1'] ? $slideOptions['bg_grad_color_1'] : 'transparent') . '"';
//                                }
//                                if ($slideOptions['bg_grad_color_2'] !== '') {
                                    $slideBGDataAttrs .= ' data-color-final="' . ($slideOptions['bg_grad_color_2'] ? $slideOptions['bg_grad_color_2'] : 'transparent') . '"';
//                                }
                                $slideBGDataAttrs .= ' data-position="' . ($slideOptions['bg_grad_angle'] ? $slideOptions['bg_grad_angle'] : '0') . 'deg"';
                                echo '<div ' . $slideBGDataAttrs . '></div>';
                            }
                        }
                        // Background Image
                        if (isset($slideOptions['bg_image_type'])) {

                            $slideBGDataAttrs = ' data-type="image"';
                            $slideBGDataAttrs .= ' data-fit="' . $slideOptions['bg_fit'] . '"';
                            if ($slideOptions['bg_fit'] === 'percentage') {
                                $slideBGDataAttrs .= ' data-fit-x="' . $slideOptions['bg_fit_x'] . '"';
                                $slideBGDataAttrs .= ' data-fit-y="' . $slideOptions['bg_fit_y'] . '"';
                            }
                            $slideBGDataAttrs .= ' data-position="' . $slideOptions['bg_position'] . '"';
                            if ($slideOptions['bg_position'] === 'percentage') {
                                $slideBGDataAttrs .= ' data-position-x="' . $slideOptions['bg_position_x'] . '"';
                                $slideBGDataAttrs .= ' data-position-y="' . $slideOptions['bg_position_y'] . '"';
                            }
                            $slideBGDataAttrs .= ' data-repeat="' . $slideOptions['bg_repeat'] . '"';

                            // Background Image Media Library
                            if ($slideOptions['bg_image_type'] === 'library' && $slideOptions['bg_image_id'] !== '') {
                                $image_attributes = wp_get_attachment_image_src( $slideOptions['bg_image_id'], 'full' );
                                if ($image_attributes) {
                                    $slideBGDataAttrs .= ' data-src="' . $image_attributes[0] . '"';
                                    echo '<div ' . $slideBGDataAttrs . '></div>';
                                }
                            }
                            // Background Image External
                            if ($slideOptions['bg_image_type'] === 'external' && $slideOptions['bg_image_url'] !== '') {
                                $slideBGDataAttrs .= ' data-src="' . $slideOptions['bg_image_url'] . '"';
                                echo '<div ' . $slideBGDataAttrs . '></div>';
                            }

                        }

                        // Background Video
                        if ( !$edit_mode
                            && ( (isset($slideOptions['bg_video_src_mp4']) && $slideOptions['bg_video_src_mp4'] !== '')
                                || (isset($slideOptions['bg_video_src_webm']) && $slideOptions['bg_video_src_webm'] !== '')
                                || (isset($slideOptions['bg_video_src_ogg']) && $slideOptions['bg_video_src_ogg'] !== '')
                            )
                        ) {
                            $slideBGDataAttrs = ' data-type="video"';
                            $slideBGDataAttrs .= ' data-src-mp4="' . trim($slideOptions['bg_video_src_mp4']) . '"';
                            $slideBGDataAttrs .= ' data-src-webm="' . trim($slideOptions['bg_video_src_webm']) . '"';
                            $slideBGDataAttrs .= ' data-src-ogg="' . trim($slideOptions['bg_video_src_ogg']) . '"';
                            $slideBGDataAttrs .= ' data-loop="' . ($slideOptions['bg_video_loop'] ? 'true' : 'false') . '"';
                            $slideBGDataAttrs .= ' data-mute="' . ($slideOptions['bg_video_mute'] ? 'true' : 'false') . '"';
                            $slideBGDataAttrs .= ' data-fillmode="' . $slideOptions['bg_video_fillmode'] . '"';
                            $slideBGDataAttrs .= ' data-cover="' . ($slideOptions['bg_video_cover'] ? 'true' : 'false') . '"';
                            $slideBGDataAttrs .= ' data-cover-type="' . $slideOptions['bg_video_cover_type'] . '"';
                            $slideBGDataAttrs .= ' data-autoplay="' . (!MPSLSharing::$isMPCEEditor ? 'true' : 'false') . '"';
                            echo '<div ' . $slideBGDataAttrs . '></div>';
                        }
                        ?>
                    </div>
                    <div class="layers">
                        <?php $layers = $slide['layers'];
//                        var_dump($layers);exit;
                        if (!empty($layers)) {
                            foreach ($layers as $layer) {
                                $layerDataAttrs = '';
                                $layerContent = '';
                                $style = '';

	                            $layerPreset = '';
	                            if ($layer['preset']) {
		                            if ($layer['preset'] === 'private') {
			                            $layerPreset = $layer['private_preset_class'];
			                            if ($edit_mode && $layerPreset && isset($layer['private_styles'])) {
				                            $emPrivatePresets[$layerPreset] = $layer['private_styles'];
			                            }
		                            } else {
			                            $layerPreset = $layer['preset'];
		                            }
	                            }
	                            $layerPreset = 'mpsl-layer ' . $layerPreset;
	                            /*if ($edit_mode && $layerPreset && isset($layer['private_styles'])) {
		                            $emPrivatePresets[$layer['private_preset_class']] = $layer['private_styles'];
	                            }*/

                                // Type dependenced attributes
                                switch ($layer['type']) {
                                    case 'html' :
                                        $layerDataAttrs .= ' data-type="' . $layer['type'] . '"';
                                        $layerContent = $layer['text'];
                                        $style = $layer['html_style'];
                                        break;
                                    case 'image' :
                                        $layerDataAttrs .= ' data-type="' . $layer['type'] . '"';
                                        $image_attributes = wp_get_attachment_image_src($layer['image_id'], 'full');
                                        if ($image_attributes) {
//                                            $layerDataAttrs .= ' data-src="' . $image_attributes[0] . '"';
                                            $layerDataAttrs .= ' src="' . $image_attributes[0] . '"';
                                        }
                                        $layerDataAttrs .= ' data-width="' . $layer['width'] . '"';
                                        $layerDataAttrs .= ' data-link="' . $layer['image_link'] . '"';
                                        $target = (isset($layer['image_target']) && $layer['image_target'] === true) ? '_blank' : '_self';
                                        $layerDataAttrs .= ' data-target="' . $target . '"';

										$imageLinkClasses = trim($layer['image_link_classes']);
										if (trim($layer['image_link'])) {
											$imageLinkClasses .= ' ' . $layerPreset;
											$layerPreset = '';
										}
                                        $layerDataAttrs .= ' data-link-class="' . $imageLinkClasses . '"';
                                        break;
                                    case 'button' :
                                        $layerDataAttrs .= ' data-type="' . $layer['type'] . '"';
                                        $layerDataAttrs .= ' data-link="' . $layer['button_link'] . '"';
                                        $target = (isset($layer['button_target']) && $layer['button_target'] === true) ? '_blank' : '_self';
                                        $layerDataAttrs .= ' data-target="' . $target . '"';
                                        $layerContent = $layer['button_text'];
                                        $style = $layer['button_style'];
                                        break;
                                    case 'video' :
                                        $layer['video_preview_image'] = trim($layer['video_preview_image']);

                                        switch ($layer['video_type']) {
                                            case 'html' :
                                                $layerDataAttrs .= ' data-type="video"';
                                                $layerDataAttrs .= ' data-src-mp4="' . trim($layer['video_src_mp4']) . '"';
                                                $layerDataAttrs .= ' data-src-webm="' . trim($layer['video_src_webm']) . '"';
                                                $layerDataAttrs .= ' data-src-ogv="' . trim($layer['video_src_ogg']) . '"';
                                                $layerDataAttrs .= ' data-controls="' . ($layer['video_html_hide_controls'] ? 'false' : 'true') . '"';
//                                            $attachmentSrc = wp_get_attachment_url($layer['video_id']);
//                                            $mimeType = get_post_mime_type($layer['video_id']);
//                                            switch($mimeType) {
//                                                case 'video/mp4' :
//                                                    $layerDataAttrs .= ' data-src-mp4="' . $attachmentSrc . '"';
//                                                    break;
//                                                case 'video/ogg' :
//                                                    $layerDataAttrs .= ' data-src-ogv="' . $attachmentSrc . '"';
//                                                    break;
//                                                case 'video/webm' :
//                                                    $layerDataAttrs .= ' data-src-webm="' . $attachmentSrc . '"';
//                                                    break;
//                                                default:
//                                                    $layerDataAttrs .= ' data-src-mp4="' . $attachmentSrc . '"';
//                                                    break;
//                                            }
                                                break;
                                            case 'youtube' :
                                                $layerDataAttrs .= ' data-type="youtube"';
                                                $layerDataAttrs .= ' data-src="' . $layer['youtube_src'] . '"';
                                                $layerDataAttrs .= ' data-controls="' . ($layer['video_youtube_hide_controls'] ? 'false' : 'true') . '"';

                                                if (empty($layer['video_preview_image'])) {
                                                    $youtubeDataApi = MPSLYoutubeDataApi::getInstance();
                                                    $youtubeThumbnail = $youtubeDataApi->getThumbnail($layer['youtube_src']);
                                                    if (false !== $youtubeThumbnail) {
                                                        $layer['video_preview_image'] = $youtubeThumbnail;
                                                    }
                                                }

                                                break;
                                            case 'vimeo' :
                                                $layerDataAttrs .= ' data-type="vimeo"';
                                                $layerDataAttrs .= ' data-src="' . $layer['vimeo_src'] . '"';

                                                if (empty($layer['video_preview_image'])) {
                                                    $vimeoOEmbedApi = MPSLVimeoOEmbedApi::getInstance();
                                                    $vimeoThumbnail = $vimeoOEmbedApi->getThumbnail($layer['vimeo_src']);
                                                    if (false !== $vimeoThumbnail) {
                                                        $layer['video_preview_image'] = $vimeoThumbnail;
                                                    }
                                                }

                                                break;
                                        }
                                        if ($layer['video_width'] !== '') {
                                            $layerDataAttrs .= ' data-width="' . $layer['video_width'] . '"';
                                        }
                                        if ($layer['video_height'] !== '') {
                                            $layerDataAttrs .= ' data-height="' . $layer['video_height'] . '"';
                                        }
                                        $layerDataAttrs .= ' data-poster="' . $layer['video_preview_image'] . '"';
                                        $layerDataAttrs .= ' data-autoplay="' . (($layer['video_autoplay'] && !MPSLSharing::$isMPCEEditor) ? 'true' : 'false') . '"';
                                        $layerDataAttrs .= ' data-loop="' . ($layer['video_loop'] ? 'true' : 'false') . '"';
                                        $layerDataAttrs .= ' data-mute="' . ($layer['video_mute'] ? 'true' : 'false') . '"';
                                        $layerDataAttrs .= ' data-disable-mobile="' . ($layer['video_disable_mobile'] ? 'true' : 'false') . '"';
                                }
                                // Position
                                /*switch($layer['vertical_position']) {
                                    case 'top' : $layerDataAttrs .= ' data-offset-top="' . $layer['top'] . '"'; break;
                                    case 'bottom' : $layerDataAttrs .= ' data-offset-bottom="' . $layer['bottom'] . '"'; break;
                                }
                                switch($layer['horizontal_position']) {
                                    case 'left' : $layerDataAttrs .= ' data-offset-left="' . $layer['left'] . '"'; break;
                                    case 'right' : $layerDataAttrs .= ' data-offset-right="' . $layer['right'] . '"'; break;
                                }*/
                                $layerDataAttrs .= ' data-align-horizontal="' . $layer['hor_align'] . '"';
                                $layerDataAttrs .= ' data-align-vertical="' . $layer['vert_align'] . '"';
                                $layerDataAttrs .= ' data-offset-x="' . $layer['offset_x'] . '"';
                                $layerDataAttrs .= ' data-offset-y="' . $layer['offset_y'] . '"';

                                // Animation
                                $layerDataAttrs .= ' data-animation="' . $layer['start_animation'] . '"';
                                // Timing and Easings
                                $layerDataAttrs .= ' data-timing-function="' . $layer['start_timing_function'] . '"';
                                $layerDataAttrs .= ' data-duration="' . $layer['start_duration'] . '"';

                                $layerDataAttrs .= ' data-leave-animation="' . $layer['end_animation'] . '"';
                                $layerDataAttrs .= ' data-leave-timing-function="' . $layer['end_timing_function'] . '"';
                                $layerDataAttrs .= ' data-leave-duration="' . $layer['end_duration'] . '"';

                                $layerDataAttrs .= ' data-delay="' . (!MPSLSharing::$isMPCEEditor ? $layer['start'] : '0') . '"';
                                if ($layer['end'] !== '0') {
                                    $layerDataAttrs .= ' data-leave-delay="' . $layer['end'] . '"';
                                }

                                $layerDataAttrs .= ' data-class="' . trim($style . ' ' . $layer['classes'] . ' ' . $layerPreset) . '"';
                                ?>
                                <?php if ($layer['type'] === 'image') { ?>
                                    <img class="layer" <?php echo $layerDataAttrs; ?> />
                                <?php } else { ?>
                                    <div class="layer" <?php echo $layerDataAttrs; ?>><?php echo $layerContent; ?></div>
                                <?php }
                            }
                        } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php

/*$privateFonts = array();
if ($edit_mode) {
	if (count($emPrivatePresets)) {
		foreach ($emPrivatePresets as $privatePreset) {
			$privateFonts = array_merge_recursive($privateFonts, $layerPresets->getFontsByPreset($privatePreset));
		}
	}
}*/

// Get all presets fonts (except private)
$allPresetFonts = $edit_mode ? $layerPresets->getAllPresetFonts() : array();

// Format fonts
//$sliderFonts = array_merge_recursive($sliderFonts, $privateFonts);
$sliderFonts = array_merge_recursive($sliderFonts, $allPresetFonts);
$sliderFonts = MPSLLayerPresetOptions::fontsUnique($sliderFonts);
$fontsUrl = array();
foreach ($sliderFonts as $fontName => &$fontData) {
	$fontData['variants'] = array_unique($fontData['variants']); // Unique variants

	$fontsUrlPart = $fontName;
	if (count($fontData['variants'])) {
		$fontsUrlPart .= ':' . implode(',', $fontData['variants']);
	}
	$fontsUrl[] = urlencode($fontsUrlPart);
}
if (count($fontsUrl)) {
	$fontsUrl = sprintf('//fonts.googleapis.com/css?family=%s', implode('|', $fontsUrl));
} else {
	$fontsUrl = '';
}

$hideScriptClasses = 'motopress-hide-script mpsl-hide-script';
if ($edit_mode) {
	MPSLAdminSharing::$gFontsUrl = $fontsUrl;
} elseif ($fontsUrl) { ?>
	<p class="<?php echo $hideScriptClasses; ?>">
		<script type="text/javascript" id='mpsl-slider-fonts-load-<?php echo $sliderWrapperId; ?>'>
			var font = document.createElement('link');
			font.rel = 'stylesheet';
			font.type = 'text/css';
			font.className = 'mpsl-fonts-link';
			font.href = '<?php echo $fontsUrl; ?>';
			document.getElementsByTagName('head')[0].appendChild(font);
		</script>
	</p>
<?php }

if (defined('DOING_AJAX') && DOING_AJAX) { ?>
	<p class="<?php echo $hideScriptClasses; ?>">
	    <script type="text/javascript" id='mpsl-init-slider-<?php echo $sliderWrapperId; ?>'>
	        jQuery(document).ready(function($) {
	            MPSLManager.initSlider($('#<?php echo $sliderWrapperId; ?>')[0]);
	        });
	    </script>
	</p>
<?php } else {
	if ($edit_mode) {
		MPSLAdminSharing::$defaultPresets = $layerPresets->compile($layerPresets->getDefaultPresets(), false, true);
		MPSLAdminSharing::$presets = $layerPresets->compile($layerPresets->getPresets(), false, true);
		MPSLAdminSharing::$privatePresets = $layerPresets->compile($emPrivatePresets, true, true);
	}
} ?>
<p class="<?php echo $hideScriptClasses; ?>">
	<script type="text/javascript" id='mpsl-fix-height-<?php echo $sliderWrapperId; ?>'>
	    var aspect = <?php echo $aspect; ?>;
	    var sliderWrapper = document.getElementById('<?php echo $sliderWrapperId; ?>');
	    var outerWidth = sliderWrapper.offsetWidth;
	    var curHeight = outerWidth * aspect;
	    sliderWrapper.querySelector('[data-motoslider]').height = curHeight + 'px';
	</script>
</p>