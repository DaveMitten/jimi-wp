<?php
/**
 * @package    Cherry_Framework
 * @subpackage Class
 * @author     Cherry Team <support@cherryframework.com>
 * @copyright  Copyright (c) 2012 - 2015, Cherry Team
 * @link       http://www.cherryframework.com/
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Header Menu static.
 */
class cherry_header_menu_static extends cherry_register_static {

	/**
	 * Callback-method for registered static.
	 *
	 * @since 4.0.0
	 */
	public function callback() {
		cherry_get_menu_template( 'primary' );
	}

}

/**
 * Registration for Header Menu static.
 */
new cherry_header_menu_static(
	array(
		'id'      => 'header_menu',
		'name'    => __( 'Header Menu', 'cherry' ),
		'options' => array(
			'col-lg'   => 'col-lg-7',
			'col-md'   => 'col-md-7',
			'col-sm'   => 'col-sm-10',
			'col-xs'   => 'col-xs-10',
			'position' => 2,
			'area'     => 'header-top',
		)
	)
);