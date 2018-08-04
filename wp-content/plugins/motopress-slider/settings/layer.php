<?php
return array(
    'general' => array(
        'title' => __('Layer General Parameters', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'options' => array(
            'type' => array(
                'type' => 'select',
                'default' => 'html',
                'list' => array(
                    'html' => 'html',
                    'image' => 'image',
                    'button' => 'button',
                    'video' => 'video'
                ),
                'hidden' => true
            ),
//	        'id' => array(
//                'type' => 'text',
//                'default' => 0,
//                'hidden' => true
//            ),
//            'order' => array(
//                'type' => 'number',
//                'default' => 0,
//                'hidden' => true
//            ),
//            'style' => array(
//                'type' => 'select',
//                'label' => __('Style', MPSL_TEXTDOMAIN),
//                'description' => __('Choose style', MPSL_TEXTDOMAIN),
//                'default' => 'green',
//                'disabled' => false,
//                'list' => array(
//                    'red' => __('Red', MPSL_TEXTDOMAIN),
//                    'blue' => __('Blue', MPSL_TEXTDOMAIN),
//                    'green' => __('Green', MPSL_TEXTDOMAIN),
//                )
//            ),
//            'alt' => array(
//                'type' => 'text',
//                'label' => __('Alt Text', MPSL_TEXTDOMAIN),
//                'default' => '',
//                'dependency' => array(
//                    'parameter' => 'style',
//                    'value' => 'green'
//                )
//            ),

            'align' => array(
                'type' => 'align_table',
                'default' => array(
                    'vert' => 'middle',
                    'hor' => 'center'
                ),
                'options' => array(
                    'vert_align' => array(
                        'type' => 'hidden',
                        'default' => 'middle'
                    ),
                    'hor_align' => array(
                        'type' => 'hidden',
                        'default' => 'center'
                    ),
                    'offset_x' => array(
                        'type' => 'number',
                        'default' => 0,
                        'label2' => __('X:', MPSL_TEXTDOMAIN)
                    ),
                    'offset_y' => array(
                        'type' => 'number',
                        'default' => 0,
                        'label2' => __('Y:', MPSL_TEXTDOMAIN)
                    )
                )
            ),

            'start_animation' => array(
                'type' => 'select',
                'label2' => __('Start Animation :', MPSL_TEXTDOMAIN),
                'default' => 'fadeIn',
                'list' => array(
                    'bounceIn' => __('bounceIn', MPSL_TEXTDOMAIN),
                    'bounceInDown' => __('bounceInDown', MPSL_TEXTDOMAIN),
                    'bounceInLeft' => __('bounceInLeft', MPSL_TEXTDOMAIN),
                    'bounceInRight' => __('bounceInRight', MPSL_TEXTDOMAIN),
                    'bounceInUp' => __('bounceInUp', MPSL_TEXTDOMAIN),
                    'fadeIn' => __('fadeIn', MPSL_TEXTDOMAIN),
                    'fadeInDown' => __('fadeInDown', MPSL_TEXTDOMAIN),
                    'fadeInDownBig' => __('fadeInDownBig', MPSL_TEXTDOMAIN),
                    'fadeInLeft' => __('fadeInLeft', MPSL_TEXTDOMAIN),
                    'fadeInLeftBig' => __('fadeInLeftBig', MPSL_TEXTDOMAIN),
                    'fadeInRight' => __('fadeInRight', MPSL_TEXTDOMAIN),
                    'fadeInRightBig' => __('fadeInRightBig', MPSL_TEXTDOMAIN),
                    'fadeInUp' => __('fadeInUp', MPSL_TEXTDOMAIN),
                    'fadeInUpBig' => __('fadeInUpBig', MPSL_TEXTDOMAIN),
                    'flip' => __('flip', MPSL_TEXTDOMAIN),
                    'flipInX' => __('flipInX', MPSL_TEXTDOMAIN),
                    'flipInY' => __('flipInY', MPSL_TEXTDOMAIN),
                    'lightSpeedIn' => __('lightSpeedIn', MPSL_TEXTDOMAIN),
                    'rotateIn' => __('rotateIn', MPSL_TEXTDOMAIN),
                    'rotateInDownLeft' => __('rotateInDownLeft', MPSL_TEXTDOMAIN),
                    'rotateInDownRight' => __('rotateInDownRight', MPSL_TEXTDOMAIN),
                    'rotateInUpLeft' => __('rotateInUpLeft', MPSL_TEXTDOMAIN),
                    'rotateInUpRight' => __('rotateInUpRight', MPSL_TEXTDOMAIN),
                    'rollIn' => __('rollIn', MPSL_TEXTDOMAIN),
                    'zoomIn' => __('zoomIn', MPSL_TEXTDOMAIN),
                    'zoomInDown' => __('zoomInDown', MPSL_TEXTDOMAIN),
                    'zoomInLeft' => __('zoomInLeft', MPSL_TEXTDOMAIN),
                    'zoomInRight' => __('zoomInRight', MPSL_TEXTDOMAIN),
                    'zoomInUp' => __('zoomInUp', MPSL_TEXTDOMAIN)
                )
            ),
            'start_timing_function' => array(
                'type' => 'select',
                'label2' => __('Easing :', MPSL_TEXTDOMAIN),
                'default' => 'linear',
                'list' => array(
                    'linear' => __('linear', MPSL_TEXTDOMAIN),
                    'ease' => __('ease', MPSL_TEXTDOMAIN),
                    'easeIn' => __('easeIn', MPSL_TEXTDOMAIN),
                    'easeInOut' => __('easeInOut', MPSL_TEXTDOMAIN),
                    'easeInQuad' => __('easeInQuad', MPSL_TEXTDOMAIN),
                    'easeInCubic' => __('easeInCubic', MPSL_TEXTDOMAIN),
                    'easeInQuart' => __('easeInQuart', MPSL_TEXTDOMAIN),
                    'easeInQuint' => __('easeInQuint', MPSL_TEXTDOMAIN),
                    'easeInSine' => __('easeInSine', MPSL_TEXTDOMAIN),
                    'easeInExpo' => __('easeInExpo', MPSL_TEXTDOMAIN),
                    'easeInCirc' => __('easeInCirc', MPSL_TEXTDOMAIN),
                    'easeInBack' => __('easeInBack', MPSL_TEXTDOMAIN),
                    'easeInOutQuad' => __('easeInOutQuad', MPSL_TEXTDOMAIN),
                    'easeInOutCubic' => __('easeInOutCubic', MPSL_TEXTDOMAIN),
                    'easeInOutQuart' => __('easeInOutQuart', MPSL_TEXTDOMAIN),
                    'easeInOutQuint' => __('easeInOutQuint', MPSL_TEXTDOMAIN),
                    'easeInOutSine' => __('easeInOutSine', MPSL_TEXTDOMAIN),
                    'easeInOutExpo' => __('easeInOutExpo', MPSL_TEXTDOMAIN),
                    'easeInOutCirc' => __('easeInOutCirc', MPSL_TEXTDOMAIN),
                    'easeInOutBack' => __('easeInOutBack', MPSL_TEXTDOMAIN),
                )
            ),
            'start_duration' => array(
                'type' => 'number',
                'label2' => __('duration (ms): ', MPSL_TEXTDOMAIN),
                'default' => 1000,
                'min' => 0
            ),
            'end_animation' => array(
                'type' => 'select',
                'label2' => __('End Animation :', MPSL_TEXTDOMAIN),
                'default' => 'auto',
                'list' => array(
                    'auto' => __('auto', MPSL_TEXTDOMAIN),
                    'bounceOut' => __('bounceOut', MPSL_TEXTDOMAIN),
                    'bounceOutDown' => __('bounceOutDown', MPSL_TEXTDOMAIN),
                    'bounceOutLeft' => __('bounceOutLeft', MPSL_TEXTDOMAIN),
                    'bounceOutRight' => __('bounceOutRight', MPSL_TEXTDOMAIN),
                    'bounceOutUp' => __('bounceOutUp', MPSL_TEXTDOMAIN),
                    'fadeOut' => __('fadeOut', MPSL_TEXTDOMAIN),
                    'fadeOutDown' => __('fadeOutDown', MPSL_TEXTDOMAIN),
                    'fadeOutDownBig' => __('fadeOutDownBig', MPSL_TEXTDOMAIN),
                    'fadeOutLeft' => __('fadeOutLeft', MPSL_TEXTDOMAIN),
                    'fadeOutLeftBig' => __('fadeOutLeftBig', MPSL_TEXTDOMAIN),
                    'fadeOutRight' => __('fadeOutRight', MPSL_TEXTDOMAIN),
                    'fadeOutUp' => __('fadeOutUp', MPSL_TEXTDOMAIN),
                    'fadeOutUpBig' => __('fadeOutUpBig', MPSL_TEXTDOMAIN),
                    'flip' => __('flip', MPSL_TEXTDOMAIN),
                    'flipOutX' => __('flipOutX', MPSL_TEXTDOMAIN),
                    'flipOutY' => __('flipOutY', MPSL_TEXTDOMAIN),
                    'lightSpeedOut' => __('lightSpeedOut', MPSL_TEXTDOMAIN),
                    'rotateOut' => __('rotateOut', MPSL_TEXTDOMAIN),
                    'rotateOutDownLeft' => __('rotateOutDownLeft', MPSL_TEXTDOMAIN),
                    'rotateOutDownRight' => __('rotateOutDownRight', MPSL_TEXTDOMAIN),
                    'rotateOutUpLeft' => __('rotateOutUpLeft', MPSL_TEXTDOMAIN),
                    'rotateOutUpRight' => __('rotateOutUpRight', MPSL_TEXTDOMAIN),
                    'rollOut' => __('rollOut', MPSL_TEXTDOMAIN),
                    'zoomOut' => __('zoomOut', MPSL_TEXTDOMAIN),
                    'zoomOutDown' => __('zoomOutDown', MPSL_TEXTDOMAIN),
                    'zoomOutLeft' => __('zoomOutLeft', MPSL_TEXTDOMAIN),
                    'zoomOutRight' => __('zoomOutRight', MPSL_TEXTDOMAIN),
                    'zoomOutUp' => __('zoomOutUp', MPSL_TEXTDOMAIN)
                )
            ),
            'end_timing_function' => array(
                'type' => 'select',
                'label2' => __('Easing :', MPSL_TEXTDOMAIN),
                'default' => 'linear',
                'list' => array(
                    'linear' => __('linear', MPSL_TEXTDOMAIN),
                    'ease' => __('ease', MPSL_TEXTDOMAIN),
                    'easeOutQuad' => __('easeOutQuad', MPSL_TEXTDOMAIN),
                    'easeOutCubic' => __('easeOutCubic', MPSL_TEXTDOMAIN),
                    'easeOutQuart' => __('easeOutQuart', MPSL_TEXTDOMAIN),
                    'easeOutQuint' => __('easeOutQuint', MPSL_TEXTDOMAIN),
                    'easeOutSine' => __('easeOutSine', MPSL_TEXTDOMAIN),
                    'easeOutExpo' => __('easeOutExpo', MPSL_TEXTDOMAIN),
                    'easeOutCirc' => __('easeOutCirc', MPSL_TEXTDOMAIN),
                    'easeOutBack' => __('easeOutBack', MPSL_TEXTDOMAIN),
                    'easeInOutQuad' => __('easeInOutQuad', MPSL_TEXTDOMAIN),
                    'easeInOutCubic' => __('easeInOutCubic', MPSL_TEXTDOMAIN),
                    'easeInOutQuart' => __('easeInOutQuart', MPSL_TEXTDOMAIN),
                    'easeInOutQuint' => __('easeInOutQuint', MPSL_TEXTDOMAIN),
                    'easeInOutSine' => __('easeInOutSine', MPSL_TEXTDOMAIN),
                    'easeInOutExpo' => __('easeInOutExpo', MPSL_TEXTDOMAIN),
                    'easeInOutCirc' => __('easeInOutCirc', MPSL_TEXTDOMAIN),
                    'easeInOutBack' => __('easeInOutBack', MPSL_TEXTDOMAIN),
                )
            ),
            'end_duration' => array(
                'type' => 'number',
                'label2' => __('duration (ms): ', MPSL_TEXTDOMAIN),
                'default' => 1000,
                'min' => 0
            ),
            'start' => array(
                'type' => 'number',
                'label2' => __('Display at (ms): ', MPSL_TEXTDOMAIN),
                'default' => 1000,
                'min' => 0,
//                'max' => 9000,
            ),
            'end' => array(
                'type' => 'number',
                'label2' => __('Hide at (ms): ', MPSL_TEXTDOMAIN),
                'default' => 0,
                'min' => 0
            ),
            'text' => array(
                'type' => 'textarea',
                'label' => __('Text/HTML', MPSL_TEXTDOMAIN),
                'default' => __('lorem ipsum', MPSL_TEXTDOMAIN),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'html'
                )
            ),
            'button_text' => array(
                'type' => 'text',
                'label' => __('Button Text', MPSL_TEXTDOMAIN),
                'default' => __('Button', MPSL_TEXTDOMAIN),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'button'
                )
            ),
            'button_link' => array(
                'type' => 'text',
                'label' => __('Link:', MPSL_TEXTDOMAIN),
                'default' => '#',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'button'
                )
            ),
            'button_target' => array(
                'type' => 'checkbox',
                'label2' => __('Open in new window', MPSL_TEXTDOMAIN),
                'default' => 'false',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'button'
                )
            ),
            'image_id' => array(
                'type' => 'library_image',
//                'label2' => __('Image', MPSL_TEXTDOMAIN),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                ),
                'helpers' => array('image_url'),
                'button_label' => __('Select Image', MPSL_TEXTDOMAIN),
                'select_label' => __('Select Image', MPSL_TEXTDOMAIN)
            ),
            'image_url' => array(
                'type' => 'hidden',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                ),
            ),
	        'image_link' => array(
                'type' => 'text',
                'label' => __('Link:', MPSL_TEXTDOMAIN),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                )
            ),
            'image_target' => array(
                'type' => 'checkbox',
                'label2' => __('Open in new window', MPSL_TEXTDOMAIN),
                'default' => 'false',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                )
            ),

            'video_type' => array(
                'type' => 'button_group',
                'default' => 'youtube',
                'list' => array(
                    'youtube' => __('Youtube', MPSL_TEXTDOMAIN),
                    'vimeo' => __('Vimeo', MPSL_TEXTDOMAIN),
                    'html' => __('Media Library', MPSL_TEXTDOMAIN)
                ),
                'button_size' => 'large',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
//            'video_id' => array(
//                'type' => 'library_video',
//                'default' => '',
//                'dependency' => array(
//                    'parameter' => 'video_type',
//                    'value' => 'html'
//                ),
//                'button_label' => __('Select Video', MPSL_TEXTDOMAIN)
//            ),
            'video_src_mp4' => array(
                'type' => 'text',
                'default' => '',
                'label' => __('Source MP4: ', MPSL_TEXTDOMAIN),
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'html'
                )
            ),
            'video_src_webm' => array(
                'type' => 'text',
                'default' => '',
                'label' => __('Source WEBM: ', MPSL_TEXTDOMAIN),
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'html'
                )
            ),
            'video_src_ogg' => array(
                'type' => 'text',
                'default' => '',
                'label' => __('Source OGG: ', MPSL_TEXTDOMAIN),
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'html'
                )
            ),
            'youtube_src' => array(
                'type' => 'text',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'youtube'
                )
            ),
            'vimeo_src' => array(
                'type' => 'text',
                'default'=> '',
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'vimeo'
                )
            ),
            'video_preview_image' => array(
                'type' => 'text',
                'default' => '',
                'label' => __('Preview Image URL:', MPSL_TEXTDOMAIN),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_width' => array(
                'type' => 'number',
                'label2' => 'width',
                'default' => 1,
//                'min' => 1,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_height' => array(
                'type' => 'number',
                'label2' => 'height',
                'default' => 1,
//                'min' => 1,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_autoplay' => array(
                'type' => 'checkbox',
                'label' => __('Autoplay', MPSL_TEXTDOMAIN),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
//            'video_loop' => array(
//                'type' => 'select',
//                'label' => __('Loop', MPSL_TEXTDOMAIN),
//                'default' => 'disabled',
//                'list' => array(
//                    'disabled' => __('disabled', MPSL_TEXTDOMAIN),
//                    'loop' => __('Loop', MPSL_TEXTDOMAIN)
//                ),
//                'dependency' => array(
//                    'parameter' => 'type',
//                    'value' => 'video'
//                )
//            ),
            'video_loop' => array(
                'type' => 'checkbox',
                'label' => __('Loop', MPSL_TEXTDOMAIN),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_html_hide_controls' => array(
                'type' => 'checkbox',
                'label' => __('Hide Controls: ', MPSL_TEXTDOMAIN),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'html'
                )
            ),
            'video_youtube_hide_controls' => array(
                'type' => 'checkbox',
                'label' => __('Hide Controls: ', MPSL_TEXTDOMAIN),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'youtube'
                )
            ),
            'video_mute' => array(
                'type' => 'checkbox',
                'label' => __('Mute: ', MPSL_TEXTDOMAIN),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_disable_mobile' => array(
                'type' => 'checkbox',
                'label' => __('Disable Mobile: ', MPSL_TEXTDOMAIN),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'width' => array(
                'type' => 'number',
                'label2' => __('width', MPSL_TEXTDOMAIN),
//                'default' => 300,
                'default' => '',
                'min' => 1,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                )
            ),
            'preset' => array(
                'type' => 'style_editor',
                'label2' => __('Style: ', MPSL_TEXTDOMAIN),
                'edit_label' => __('Edit', MPSL_TEXTDOMAIN),
                'remove_label' => __('Remove', MPSL_TEXTDOMAIN),
	            'helpers' => array('private_styles'),
	            'default' => '',
            ),
            'private_preset_class' => array(
                'type' => 'hidden',
                'default' => ''
            ),
            'private_styles' => array(
                'type' => 'multiple',
                'default' => array() // JSON
            ),
	        'classes' => array(
                'type' => 'text',
                'label2' => __('Custom Classes: ', MPSL_TEXTDOMAIN),
                'default' => ''
            ),
	        'image_link_classes' => array(
                'type' => 'text',
                'label2' => __('Link Custom Classes: ', MPSL_TEXTDOMAIN),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                )
            ),

	        // Deprecated
	        'html_style' => array(
                'type' => 'select',
                'label' => __('Theme Styles (deprecated)', MPSL_TEXTDOMAIN),
                'default' => '',
                'list' => array(
                    '' => __('none', MPSL_TEXTDOMAIN),
                    'mpsl-header-dark' => __('Header Dark', MPSL_TEXTDOMAIN),
                    'mpsl-header-white' => __('Header White', MPSL_TEXTDOMAIN),
                    'mpsl-sub-header-dark' => __('Sub-Header Dark', MPSL_TEXTDOMAIN),
                    'mpsl-sub-header-white' => __('Sub-Header White', MPSL_TEXTDOMAIN),
                    'mpsl-text-dark' => __('Text Dark', MPSL_TEXTDOMAIN),
                    'mpsl-text-white' => __('Text White', MPSL_TEXTDOMAIN),
                ),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'html'
                )
            ),
            'button_style' => array(
                'type' => 'select',
                'label' => __('Theme Styles (deprecated)', MPSL_TEXTDOMAIN),
                'default' => '',
                'list' => array(
                    '' => __('none', MPSL_TEXTDOMAIN),
                    'mpsl-button-blue' => __('Button Blue', MPSL_TEXTDOMAIN),
                    'mpsl-button-green' => __('Button Green', MPSL_TEXTDOMAIN),
                    'mpsl-button-red' => __('Button Red', MPSL_TEXTDOMAIN)
                ),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'button'
                )
            ),

        )
    ),
);
