<?php
return array(
    'main' => array(
        'title' => __('General', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
	        'fonts' => array(
                'type' => 'multiple',
                'default' => array(),
		        'hidden' => true
            ),
            'title' => array(
                'type' => 'text',
                'label' => __('Slide Title', MPSL_TEXTDOMAIN),
                'description' => __('The title of the slide that will be shown in the slides list.', MPSL_TEXTDOMAIN),
                'default' => 'Slide'
            ),
            'status' => array(
                'type' => 'button_group',
                'label' => __('Status', MPSL_TEXTDOMAIN),
                'description' => '',
                'default' => 'published',
                'button_size' => 'large',
                'list' => array(
                    'published' => __('Published', MPSL_TEXTDOMAIN),
                    'draft' => __('Draft', MPSL_TEXTDOMAIN)
                )
            ),
            /*
            'bg_type' => array(
                'type' => 'radio_group',
                'label' => __('Background type:', MPSL_TEXTDOMAIN),
                'default' => 'color',
                'list' => array(
                    'color' => __('Color', MPSL_TEXTDOMAIN),
                    'image' => __('Image', MPSL_TEXTDOMAIN),
//                    'parallax' => __('Parallax', MPSL_TEXTDOMAIN),
//                    'video' => __('Video', MPSL_TEXTDOMAIN),
//                    'youtube' => __('YouTube', MPSL_TEXTDOMAIN),
//                    'gradient' => __('Gradient', MPSL_TEXTDOMAIN)
                )
            ),
            'bg_color' => array(
                'type' => 'text',
                'label' => __('Background Color:', MPSL_TEXTDOMAIN),
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'bg_type',
                    'value' => 'color'
                )
            ),
            */
//            'bg_types' => array(
//                'type' => 'checkbox',
//                'label' => __('Background type:', MPSL_TEXTDOMAIN),
//                'default' => array('color'),
//                'list' => array(
//                    'color' => __('Color', MPSL_TEXTDOMAIN),
//                    'image' => __('Image', MPSL_TEXTDOMAIN),
////                    'parallax' => __('Parallax', MPSL_TEXTDOMAIN),
//                    'video' => __('Video', MPSL_TEXTDOMAIN),
////                    'youtube' => __('YouTube', MPSL_TEXTDOMAIN),
////                    'gradient' => __('Gradient', MPSL_TEXTDOMAIN)
//                )
//            ),
        )
    ),
    'color' => array(
        'title' => __('Color', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'bg_color_type' => array(
                'type' => 'radio_group',
                'label' => __('Background Color Type:', MPSL_TEXTDOMAIN),
                'default' => 'color',
                'list' => array(
                    'color' => __('Color', MPSL_TEXTDOMAIN),
                    'gradient' => __('Gradient', MPSL_TEXTDOMAIN)
                ),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'color'
//                )
            ),
            'bg_color' => array(
                'type' => 'color_picker',
                'label' => __('Background Color:', MPSL_TEXTDOMAIN),
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'bg_color_type',
                    'value' => 'color'
                )
            ),
            'bg_grad_color_1' => array(
                'type' => 'color_picker',
                'label' => __('Gradient color 1:', MPSL_TEXTDOMAIN),
                'default' => 'white',
                'dependency' => array(
                    'parameter' => 'bg_color_type',
                    'value' => 'gradient'
                )
            ),
            'bg_grad_color_2' => array(
                'type' => 'color_picker',
                'label' => __('Gradient color 2:', MPSL_TEXTDOMAIN),
                'default' => 'black',
                'dependency' => array(
                    'parameter' => 'bg_color_type',
                    'value' => 'gradient'
                )
            ),
            'bg_grad_angle' => array(
                'type' => 'number',
                'label' => __('Gradient angle:', MPSL_TEXTDOMAIN),
                'default' => 0,
                'dependency' => array(
                    'parameter' => 'bg_color_type',
                    'value' => 'gradient'
                )
            ),
        )
    ),
    'image' => array(
        'title' => __('Image', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'bg_image_type' => array(
                'type' => 'radio_group',
                'label' => __('Background Image:', MPSL_TEXTDOMAIN),
                'description' => '',
                'default' => 'library',
                'disabled' => false,
                'list' => array(
                    'library' => __('Media Library', MPSL_TEXTDOMAIN),
                    'external' => __('External URL', MPSL_TEXTDOMAIN),
                ),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'image'
//                )
            ),
            'bg_image_id' => array(
                'type' => 'library_image',
//                'label' => __('Image background', MPSL_TEXTDOMAIN),
                'default' => '',
                'label' => '',
                'button_label' => __('Browse...', MPSL_TEXTDOMAIN),
                'select_label' => __('Insert image', MPSL_TEXTDOMAIN),
                'can_remove' => true,
                'dependency' => array(
                    'parameter' => 'bg_image_type',
                    'value' => 'library'
                ),
                'helpers' => array('bg_internal_image_url')
            ),
            'bg_internal_image_url' => array(
                'type' => 'hidden',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'bg_image_type',
                    'value' => 'library'
                )
            ),
            'bg_image_url' => array(
                'type' => 'image_url',
//                'label' => __('Image url', MPSL_TEXTDOMAIN),
                'label' => '',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'bg_image_type',
                    'value' => 'external'
                )
            ),
            'bg_fit' => array(
                'type' => 'select',
                'label' => __('Size:', MPSL_TEXTDOMAIN),
                'description' => '',
                'default' => 'cover',
                'disabled' => false,
                'list' => array(
                    'cover' => __('cover', MPSL_TEXTDOMAIN),
                    'contain' => __('contain', MPSL_TEXTDOMAIN),
                    'percentage' => __('(%, %)', MPSL_TEXTDOMAIN),
                    'normal' => __('normal', MPSL_TEXTDOMAIN)
                ),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'image'
//                )
            ),
            'bg_fit_x' => array(
                'type' => 'number',
                'label' => __('Fit X:', MPSL_TEXTDOMAIN),
                'default' => 100,
//                'min' => 0,
//                'max' => 100,
                'dependency' => array(
                    'parameter' => 'bg_fit',
                    'value' => 'percentage'
                )
            ),
            'bg_fit_y' => array(
                'type' => 'number',
                'label' => __('Fit Y:', MPSL_TEXTDOMAIN),
                'default' => 100,
//                'min' => 0,
//                'max' => 100,
                'dependency' => array(
                    'parameter' => 'bg_fit',
                    'value' => 'percentage'
                )
            ),
            'bg_repeat' => array(
                'type' => 'select',
                'label' => __('Repeat:', MPSL_TEXTDOMAIN),
                'description' => '',
                'default' => 'no-repeat',
                'disabled' => false,
                'list' => array(
                    'no-repeat' => __('no-repeat', MPSL_TEXTDOMAIN),
                    'repeat' => __('repeat', MPSL_TEXTDOMAIN),
                    'repeat-x' => __('repeat-x', MPSL_TEXTDOMAIN),
                    'repeat-y' => __('repeat-y', MPSL_TEXTDOMAIN)
                ),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'image'
//                )
            ),
            'bg_position' => array(
                'type' => 'select',
                'label' => __('Position:', MPSL_TEXTDOMAIN),
                'description' => '',
                'default' => 'center center',
                'disabled' => false,
                'list' => array(
                    'center top' => __('center top', MPSL_TEXTDOMAIN),
                    'center bottom' => __('center bottom', MPSL_TEXTDOMAIN),
                    'center center' => __('center center', MPSL_TEXTDOMAIN),
                    'left top' => __('left top', MPSL_TEXTDOMAIN),
                    'left center' => __('left center', MPSL_TEXTDOMAIN),
                    'left bottom' => __('left bottom', MPSL_TEXTDOMAIN),
                    'right top' => __('right top', MPSL_TEXTDOMAIN),
                    'right center' => __('right center', MPSL_TEXTDOMAIN),
                    'right bottom' => __('right bottom', MPSL_TEXTDOMAIN),
                    'percentage' => __('(x%, y%)', MPSL_TEXTDOMAIN)
                ),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'image'
//                )
            ),
            'bg_position_x' => array(
                'type' => 'number',
                'label' => __('Position X:', MPSL_TEXTDOMAIN),
                'default' => 0,
//                'min' => 0,
//                'max' => 100,
                'dependency' => array(
                    'parameter' => 'bg_position',
                    'value' => 'percentage'
                )
            ),
            'bg_position_y' => array(
                'type' => 'number',
                'label' => __('Position Y:', MPSL_TEXTDOMAIN),
                'default' => 0,
//                'min' => 0,
//                'max' => 100,
                'dependency' => array(
                    'parameter' => 'bg_position',
                    'value' => 'percentage'
                )
            ),
        )
    ),
    'video' => array(
        'title' => __('Video', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            /* Video BG Start */
            'bg_video_src_mp4' => array(
                'type' => 'text',
                'default' => '',
                'label' => __('Video Source MP4: ', MPSL_TEXTDOMAIN),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'video'
//                )
            ),
            'bg_video_src_webm' => array(
                'type' => 'text',
                'default' => '',
                'label' => __('Video Source WEBM: ', MPSL_TEXTDOMAIN),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'video'
//                )
            ),
            'bg_video_src_ogg' => array(
                'type' => 'text',
                'default' => '',
                'label' => __('Video Source OGG: ', MPSL_TEXTDOMAIN),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'video'
//                )
            ),
            'bg_video_loop' => array(
                'type' => 'checkbox',
                'default' => false,
                'label' => __('Loop Video: ', MPSL_TEXTDOMAIN),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'video'
//                )
            ),
            'bg_video_mute' => array(
                'type' => 'checkbox',
                'default' => false,
                'label' => __('Mute Video: ', MPSL_TEXTDOMAIN),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'video'
//                )
            ),
            'bg_video_fillmode' => array(
                'type' => 'select',
                'default' => 'fill',
                'label' => __('Video Fillmode: ', MPSL_TEXTDOMAIN),
                'list' => array(
                    'fill' => __('Fill', MPSL_TEXTDOMAIN),
                    'fit' => __('Fit', MPSL_TEXTDOMAIN)
                ),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'video'
//                )
            ),
            'bg_video_cover' => array(
                'type' => 'checkbox',
                'default' => false,
                'label' => __('Video Cover', MPSL_TEXTDOMAIN),
//                'dependency' => array(
//                    'parameter' => 'bg_types',
//                    'value' => 'video'
//                )
            ),
            'bg_video_cover_type' => array(
                'type' => 'select',
                'default' => '',
                'label' => __('Video Cover Type', MPSL_TEXTDOMAIN),
                'list' => array(
                    '' => __('None', MPSL_TEXTDOMAIN),
                    '2x2-black' => __('2 x 2 Black', MPSL_TEXTDOMAIN),
                    '2x2-white' => __('2 x 2 White', MPSL_TEXTDOMAIN),
                    '3x3-black' => __('3 x 3 Black', MPSL_TEXTDOMAIN),
                    '3x3-white' => __('3 x 3 White', MPSL_TEXTDOMAIN)
                ),
                'dependency' => array(
                    'parameter' => 'bg_video_cover',
                    'value' => true
                )
            )
            /* Video BG End */
        )
    ),
    'link' => array(
        'title' => __('Link', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'link' => array(
                'type' => 'text',
                'label' => __('Link this slide', MPSL_TEXTDOMAIN),
                'default' => ''
            ),
            'link_target' => array(
                'type' => 'checkbox',
                'label' => '',
                'label2' => __('Open in new window', MPSL_TEXTDOMAIN),
                'default' => false
            ),
            'link_id' => array(
                'type' => 'text',
                'label' => __('Link id:', MPSL_TEXTDOMAIN),
                'default' => ''
            ),
            'link_class' => array(
                'type' => 'text',
                'label' => __('Link class:', MPSL_TEXTDOMAIN),
                'default' => ''
            ),
            'link_rel' => array(
                'type' => 'text',
                'label' => __('Link rel:', MPSL_TEXTDOMAIN),
                'default' => ''
            ),
            'link_title' => array(
                'type' => 'text',
                'label' => __('Link title:', MPSL_TEXTDOMAIN),
                'default' => ''
            ),
        )
    ),
    'visibility' => array(
        'title' => __('Visibility', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'need_logged_in' => array(
                'type' => 'checkbox',
                'label' => '',
                'label2' => __('Only logged-in users can view this slide', MPSL_TEXTDOMAIN),
                'default' => false
            ),
            'date_from' => array(
                'type' => 'datepicker',
                'label' => __('Visible from', MPSL_TEXTDOMAIN),
                'default' => '',
            ),
            'date_until' => array(
                'type' => 'datepicker',
                'label' => __('Visible until', MPSL_TEXTDOMAIN),
                'default' => '',
            ),
        )
    ),
    'misc' => array(
        'title' => __('Misc', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'slide_classes' => array(
                'type' => 'text',
                'label' => __('Class name:', MPSL_TEXTDOMAIN),
                'default' => '',
            ),
            'slide_id' => array(
                'type' => 'text',
                'label' => __('CSS id:', MPSL_TEXTDOMAIN),
                'default' => '',
            )
        )
    ),
);