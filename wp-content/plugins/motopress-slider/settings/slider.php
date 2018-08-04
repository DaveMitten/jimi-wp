<?php
return array(
    'main' => array(
        'title' => __('Slider Settings', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'options' => array(
            'title' => array(
                'type' => 'text',
                'label' => __('Slider title *:', MPSL_TEXTDOMAIN),
                'description' => __('The title of the slider. Example: Slider1', MPSL_TEXTDOMAIN),
                'default' => __('New Slider', MPSL_TEXTDOMAIN),
                'disabled' => false,
                'required' => true,
            ),
            'alias' => array(
                'type' => 'alias',
                'label' => __('Slider alias *:', MPSL_TEXTDOMAIN),
                'alias' => 'shortcode',
                'description' => __('The alias that will be used in shortcode for embedding the slider. Alias must be unique. Example: slider1', MPSL_TEXTDOMAIN),
                'default' => '',
                'disabled' => false,
                'required' => true,
            ),
            'shortcode' => array(
                'type' => 'shortcode',
                'label' => __('Slider shortcode:', MPSL_TEXTDOMAIN),
                'description' => 'Copy this shortocode and paste to your page.',
                'default' => '',
                'readonly' => true,
//                'disabled' => false,
            ),
            'full_width' => array(
                'type' => 'checkbox',
                'label' => '',
                'label2' => __('Force Full Width', MPSL_TEXTDOMAIN),
                'description' => __('Enable this option to make this slider full-width / wide-screen', MPSL_TEXTDOMAIN),
                'default' => false
            ),
            'width' => array(
                'type' => 'number',
                'label' => __('Layers Grid Size', MPSL_TEXTDOMAIN),
                'label2' => __('Width:', MPSL_TEXTDOMAIN),
                'description' => __('Initial width of the layers', MPSL_TEXTDOMAIN),
//                'pattern' => '/^(0|[1-9][0-9]*)$/',
                'default' => 960,
                'min' => 0,
//                'disabled' => false
            ),
            'height' => array(
                'type' => 'number',
                'label' => '',
                'label2' => __('Height:', MPSL_TEXTDOMAIN),
                'description' => __('Initial height of the layers', MPSL_TEXTDOMAIN),
                'default' => 350,
                'min' => 0,
//                'disabled' => false
            ),
            /*'min_height' => array(
                'type' => 'number',
                'label2' => __('Min. Height:'),
                'default' => 500
            ),*/
            'enable_timer' => array(
                'type' => 'checkbox',
                'label' => '',
                'label2' => __('Enable Slideshow', MPSL_TEXTDOMAIN),
                'default' => true,
//                'disabled' => false
            ),
            'slider_delay' => array(
                'type' => 'text',
                'label' => __('Slideshow Delay:', MPSL_TEXTDOMAIN),
                'description' => __('The time one slide stays on the screen in milliseconds', MPSL_TEXTDOMAIN),
                'default' => 7000
            ),
            'slider_animation' => array(
                'type' => 'select',
                'label' => __('Slideshow Animation:', MPSL_TEXTDOMAIN),
                'default' => 'msSlide',
                   'list' => array(
                       'msSlide' => __('Slide', MPSL_TEXTDOMAIN),
                       'msSlideFade' => __('Fade', MPSL_TEXTDOMAIN),
                       'msSlideUpDown' => __('Slide Down', MPSL_TEXTDOMAIN),
                    ),
                    //'description' => __('Select slideshow animation', MPSL_TEXTDOMAIN),
            ),
            'slider_duration' => array(
                'type' => 'text',
                'label' => __('Slideshow Duration:', MPSL_TEXTDOMAIN),
                'description' => __('Animation duration in milliseconds', MPSL_TEXTDOMAIN),
                'default' => 2000
            ),
            'slider_easing' => array(
                'type' => 'select',
                'label' => __('Slideshow Easings:', MPSL_TEXTDOMAIN),
                'default' => 'easeOutCirc',
                   'list' => array(
                       'linear' => __('linear', MPSL_TEXTDOMAIN),
                       'ease' => __('ease', MPSL_TEXTDOMAIN),
                       'easeIn' => __('easeIn', MPSL_TEXTDOMAIN),
                       'easeOut' => __('easeOut', MPSL_TEXTDOMAIN),
                       'easeInOut' => __('easeInOut', MPSL_TEXTDOMAIN),
                       'easeInQuad' => __('easeInQuad', MPSL_TEXTDOMAIN),
                       'easeInCubic' => __('easeInCubic', MPSL_TEXTDOMAIN),
                       'easeInQuart' => __('easeInQuart', MPSL_TEXTDOMAIN),
                       'easeInQuint' => __('easeInQuint', MPSL_TEXTDOMAIN),
                       'easeInSine' => __('easeInSine', MPSL_TEXTDOMAIN),
                       'easeInExpo' => __('easeInExpo', MPSL_TEXTDOMAIN),
                       'easeInCirc' => __('easeInCirc', MPSL_TEXTDOMAIN),
                       'easeInBack' => __('easeInBack', MPSL_TEXTDOMAIN),
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
                   ),
                'description' => __('<a href="https://jqueryui.com/easing/" target="_blank">Easing examples</a>', MPSL_TEXTDOMAIN),
//                'dependency' => array(
//                    'parameter' => 'slider_animation',
//                    'value' => 'msSlide'
//                ),
            ),
//            'post_slider' => array(
//                'type' => 'checkbox',
//                'label' => '',
//                'label2' => __('Post content', MPSL_TEXTDOMAIN),
//                'description' => __('Enable post slider', MPSL_TEXTDOMAIN),
//                'default' => false
//            ),

//            'slider_layout' => array(
//                'type' => 'select',
//                'label' => __('Slider Layout', MPSL_TEXTDOMAIN),
//                'default' => 'auto',
//                'list' => array(
//                    'auto' => __('Auto', MPSL_TEXTDOMAIN)
//                )
//            ),
//            'description' => array(
//                'type' => 'textarea',
//                'label' => __('Description :', MPSL_TEXTDOMAIN),
//                'description' => __('Write some description', MPSL_TEXTDOMAIN),
//                'default' => 'Default description',
////                'disabled' => false,
//            ),
//            'test' => array(
//                'type' => 'select',
//                'label' => __('Test dependency', MPSL_TEXTDOMAIN),
//                'default' => 'off',
//                'list' => array(
//                    'on' => 'On',
//                    'off' => 'Off'
//                ),
//            ),
//            'test_dependency' => array(
//                'type' => 'text',
//                'label' => __('Test dependency input', MPSL_TEXTDOMAIN),
//                'default' => 'visible',
//                'dependency' => array(
//                    'parameter' => 'test',
//                    'value' => 'on'
//                ),
//            ),
//            'radio_group' => array(
//                'type' => 'radio_group',
//                'label' => __('Test radiogroup', MPSL_TEXTDOMAIN),
//                'default' => 'one',
//                'list' => array(
//                    'one' => 'One',
//                    'two' => 'Two',
//                    'three' => 'Three',
//                )
//            ),
            'start_slide' => array(
                'type' => 'number',
                'label' => __('Start with slide:', MPSL_TEXTDOMAIN),
                'description' => __('Slide index in the list of slides', MPSL_TEXTDOMAIN),
                'default' => 1
            ),
        )
    ),

    'controls' => array(
        'title' => __('Controls', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'options' => array(
            'arrows_show' => array(
                'type' => 'checkbox',
                'label2' => __('Show arrows', MPSL_TEXTDOMAIN),
                'default' => true
            ),
            'thumbnails_show' => array(
                'type' => 'checkbox',
                'label2' => __('Show bullets', MPSL_TEXTDOMAIN),
                'default' => true
            ),
            'slideshow_timer_show' => array(
                'type' => 'checkbox',
                'label2' => __('Show slideshow timer', MPSL_TEXTDOMAIN),
                'default' => true
            ),
            'slideshow_ppb_show' => array(
                'type' => 'checkbox',
                'label2' => __('Show slideshow play/pause button', MPSL_TEXTDOMAIN),
                'default' => true
            ),
            'controls_hide_on_leave' => array(
                'type' => 'checkbox',
                'label2' => __('Hide controls when mouse leaves slider', MPSL_TEXTDOMAIN),
                'default' => false
            ),
            'hover_timer' => array(
                'type' => 'checkbox',
                'label2' => __('Pause on Hover', MPSL_TEXTDOMAIN),
                'description' => __('Pause slideshow when hover the slider', MPSL_TEXTDOMAIN),
                'default' => false
            ),
            'timer_reverse' => array(
                'type' => 'checkbox',
                'label2' => __('Reverse order of the slides', MPSL_TEXTDOMAIN),
                'description' => __('Animate slides in the reverse order', MPSL_TEXTDOMAIN),
                'default' => false
            ),
            'counter' => array(
                'type' => 'checkbox',
                'label2' => __('Show counter', MPSL_TEXTDOMAIN),
                'description' => __('Displays the number of slides', MPSL_TEXTDOMAIN),
                'default' => false
            ),
            'swipe' => array(
                'type' => 'checkbox',
                'label2' => __('Enable swipe', MPSL_TEXTDOMAIN),
                'description' => __('Turn on swipe on desktop', MPSL_TEXTDOMAIN),
                'default' => true
            ),
        )
    ),

    'appearance' => array(
        'title' => __('Appearance', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'options' => array(
            'visible_from' => array(
                'type' => 'number',
                'label' => __('Visible', MPSL_TEXTDOMAIN),
                'label2' => __('from', MPSL_TEXTDOMAIN),
                'unit' => 'px',
                'default' => '',
                'min' => 0,
            ),
            'visible_till' => array(
                'type' => 'number',
                'label' => '',
                'label2' => __('till', MPSL_TEXTDOMAIN),
                'unit' => 'px',
                'default' => '',
                'min' => 0,
            ),
            'presets' => array(
                'type' => 'action_group',
                'label' => '',
                'label2' => __('presets:', MPSL_TEXTDOMAIN),
                'default' => '',
                'list' => array(
                    'phone' => __('Phone', MPSL_TEXTDOMAIN),
                    'tablet' => __('Tablet', MPSL_TEXTDOMAIN),
                    'desktop' => __('Desktop', MPSL_TEXTDOMAIN)
                ),
                'actions' => array(
                    'phone' => array(
                        'visible_from' => '',
                        'visible_till' => 767
                    ),
                    'tablet' => array(
                        'visible_from' => 768,
                        'visible_till' => 991
                    ),
                    'desktop' => array(
                        'visible_from' => 992,
                        'visible_till' => ''
                    )
                )
            ),
            'delay_init' => array(
                'type' => 'text',
                'label' => __('Initialization delay:', MPSL_TEXTDOMAIN),
                //'description' => __('Type slider init delay', MPSL_TEXTDOMAIN),
                'default' => 0
            ),

            'scroll_init' => array(
                'type' => 'checkbox',
                'label' => '',
                'label2' => __('Initialize slider on scroll', MPSL_TEXTDOMAIN),
                //'description' => __('Enable this option to init slider on scroll', MPSL_TEXTDOMAIN),
                'default' => false
            ),
            'custom_class' => array(
                'type' => 'text',
                'label' => __('Slider custom class name', MPSL_TEXTDOMAIN),
                'default' => ''
            ),
            'custom_styles' => array(
                'type' => 'codemirror',
                'mode' => 'css',
                'label2' => __('Slider custom styles', MPSL_TEXTDOMAIN),
                'default' => ''
            ),
        )
    )

);
