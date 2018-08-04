<?php

// TODO: Cache ALL settings

$fonts = array();
if (!(defined('DOING_AJAX') and DOING_AJAX) and is_admin()) {
	$fonts = wp_cache_get('mpsl_gfonts');
	if (false === $fonts) {
		global $mpsl_settings;
		$googleFonts = file_get_contents($mpsl_settings['plugin_dir_path'] . 'vendor/googlefonts/webfonts.json');
		$googleFonts = $googleFonts ? json_decode($googleFonts, true) : array();

		$fonts[''] = '-- ' . __('SELECT', MPSL_TEXTDOMAIN) . ' --';

		if (!is_null($googleFonts) && isset($googleFonts['items'])) {
			foreach ($googleFonts['items'] as $gFont) {
				foreach ($gFont['variants'] as $key => $variant) {
					if (strpos($variant, 'italic') !== false) {
						unset($gFont['variants'][$key]);
						continue;
					}
					$gFont['variants'][$key] = str_replace('regular', 'normal', $variant);
				}
				$fonts[$gFont['family']] = array(
					'label' => $gFont['family'],
					'attrs' => array(
						'data-variants' => $gFont['variants']
					)
				);
			}
		}
		wp_cache_set('mpsl_gfonts', $fonts);
	}
}

return array(
    'font-typography' => array(
        'title' => __('Font and typography', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
	        'allow_style' => array(
		        'type' => 'checkbox',
		        'label2' => __('Enable mouse over styles', MPSL_TEXTDOMAIN),
		        'default' => true
	        ),

            'background-color' => array(
                'type' => 'color_picker',
                'label' => __('Background color:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'color' => array(
                'type' => 'color_picker',
                'label' => __('Text color:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'font-size' => array(
                'type' => 'number',
                'label' => __('Font size:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
	            'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
			'font-family' => array(
                'type' => 'font_picker',
                'label' => __('Font:', MPSL_TEXTDOMAIN),
                'default' => '',
				// regular -> normal | (?) skip italic & [number]italic | default - regular, :first or empty
	            'list' => $fonts,
	            'listAttrSettings' => array(
		            'data-variants' => array(
			            // split|json
			            // format: [index] => { key => 'key', value => 'value' }
			            'type' => 'split',
						'delimiter' => ',', // if `split`
		            )
	            ),
				'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
			'font-weight' => array(
                'type' => 'select',
                'label' => __('Weight:', MPSL_TEXTDOMAIN),
                'default' => '',
				'helpers' => array('font-family'),
				'dynamicList' => array(
					'parameter' => 'font-family',
					'attr' => 'data-variants',
					/*
					regexp instanceof RegExp
					regexp.test('str')
					str.replace(regexp, replacement)
					*/
//					'filter' => '/^((?!italic).)*$/i', // string|regexp
//					'replace' => array(
//						'value' => array(
//							'pattern' => 'regular', // string|regexp
//							'replacement' => 'normal'
//						)
//					),
				),
				'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
	        'font-style' => array(
		        'type' => 'select',
		        'label' => __('Font style:', MPSL_TEXTDOMAIN),
		        'default' => '',
		        'list' => array(
			        '' => 'Inherit',
			        'italic' => 'Italic'
		        ),
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
	        ),
//	        'font-style' => array(
//		        'type' => 'checkbox',
//		        'label' => __('Italic:', MPSL_TEXTDOMAIN),
//		        'default' => false
//	        ),
//			'white-space' => array(
//                'type' => 'checkbox',
//                'label' => __('Wordwrap:', MPSL_TEXTDOMAIN),
//                'default' => false
//            ),
            'letter-spacing' => array(
                'type' => 'number',
                'label' => __('Letter spacing:', MPSL_TEXTDOMAIN),
                'default' => '',
				'unit' => 'px',
	            'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
	        'line-height' => array(
                'type' => 'number',
                'label' => __('Line height:', MPSL_TEXTDOMAIN),
                'default' => '', // normal
		        'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
	        'text-align' => array(
                'type' => 'select',
                'label' => __('Text align:', MPSL_TEXTDOMAIN),
                'default' => '',
		        'list' => array(
			        '' => 'Inherit',
			        'left' => 'Left',
			        'center' => 'Center',
			        'right' => 'Right',
			        'justify' => 'Justify'
		        ),
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
    'text-shadow' => array(
        'title' => __('Text Shadow', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
			'text-shadow' => array(
                'type' => 'text_shadow',
                'default' => '',
				'options' => array(
					'text_shadow_color' => array(
						'type' => 'color_picker',
						'label' => __('Color:', MPSL_TEXTDOMAIN),
						'default' => ''
					),
					'text_shadow_hor_len' => array(
						'type' => 'number',
						'label' => __('Horizontal Length:', MPSL_TEXTDOMAIN),
						'default' => '',
						'unit' => 'px'
					),
					'text_shadow_vert_len' => array(
						'type' => 'number',
						'label' => __('Vertical Length:', MPSL_TEXTDOMAIN),
						'default' => '',
						'unit' => 'px'
					),
					'text_shadow_radius' => array(
						'type' => 'number',
						'label' => __('Radius:', MPSL_TEXTDOMAIN),
						'default' => '',
						'min' => 0,
						'unit' => 'px'
					)
				),
	            'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
    'border' => array(
        'title' => __('Border', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'border-style' => array(
                'type' => 'select',
                'label' => __('Border Style:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'list' => array(
		            'none' => 'None',
		            '' => 'Inherit',
		            'hidden' => 'Hidden',
		            'solid' => 'Solid',
		            'dotted' => 'Dotted',
		            'dashed' => 'Dashed',
		            'double' => 'Double',
		            'groove' => 'Groove',
		            'ridge' => 'Ridge',
		            'inset' => 'Inset',
		            'outset' => 'Outset'
	            ),
	            'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            /*'border-width' => array(
                'type' => 'number',
                'label' => __('Border Width:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px'
//	            'dependency' => array(
//                    'parameter' => 'border-style',
//                    'except' => array('none', 'hidden', 'initial', 'inherit')
//                )
            ),*/
	        'border-top-width' => array(
                'type' => 'number',
                'label' => __('Top:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-right-width' => array(
                'type' => 'number',
                'label' => __('Right:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-bottom-width' => array(
                'type' => 'number',
                'label' => __('Bottom:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-left-width' => array(
                'type' => 'number',
                'label' => __('Left:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-color' => array(
                'type' => 'color_picker',
                'label' => __('Border Color:', MPSL_TEXTDOMAIN),
                'default' => '',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-radius' => array(
                'type' => 'number',
                'label' => __('Border Radius:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
    'padding' => array(
        'title' => __('Padding', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'padding-top' => array(
                'type' => 'number',
                'label' => __('Top:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'padding-right' => array(
                'type' => 'number',
                'label' => __('Right:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'padding-bottom' => array(
                'type' => 'number',
                'label' => __('Bottom:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'padding-left' => array(
                'type' => 'number',
                'label' => __('Left:', MPSL_TEXTDOMAIN),
                'default' => '',
	            'min' => 0,
				'unit' => 'px',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
    'advanced-editor' => array(
        'title' => __('Advanced Editor', MPSL_TEXTDOMAIN),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
			'custom_styles' => array(
                'type' => 'codemirror',
                'mode' => 'css',
                'label2' => __('Custom styles', MPSL_TEXTDOMAIN),
                'default' => '',
		        'disabled_dependency' => array(
	                'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
);
