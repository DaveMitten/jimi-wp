<?php
/*
    Plugin Name: WP Contact Slider
	Plugin URI:	https://wpcontactslider.com/
    Description: Simple Contact Slider to display Contact Form 7, Gravity Forms, some other shortcodes and dispaly random Text or HTML.
    Author: wpexpertsio
	Author URI: http://www.wpexperts.io/
    Version: 2.1.6
*/

if(is_admin()){

    // Integration
    // require_once( 'inc/wpcs_freemius.php' ); // Removed in version 2.1

    // To display admin notice
    require_once( 'inc/wpcs_update_notice.php' );

}

if( ( isset($_GET['post_type']) && $_GET['post_type'] == 'wpcs' ) || ( isset($_GET['post'])  && get_post_type( $_GET['post'] ) == 'wpcs'  ) || ( isset($_POST['wpcs_lable_text_color']) || ( isset($_GET['action']) && $_GET['action'] == 'trash' ) ) ){ // checkiing

 // For meta-box support
 if( is_admin() && ! class_exists( 'RW_Meta_Box' ) )
  require_once( 'inc/meta-box/meta-box.php' );

 // Declaring Meta fields
  require_once( 'inc/wpcs_meta_fields.php' );

}

// For admin functions support
require_once( 'inc/wpcs_admin_functions.php' );

// For slider related functions
require_once( 'inc/wpcs_slider.php' );

// To call front end functions
require_once( 'inc/wpcs_frontend_functions.php' );

// Get CSS
require_once( 'inc/wpcs_enque_styles.php' );

// Get Scripts
require_once( 'inc/wpcs_enque_scripts.php' );

// Add-ons bundle sub menu
require_once( 'inc/wpcs_bundle_menu.php');

register_deactivation_hook( __FILE__, 'wpcs_deactivate' );
/**
 * @usage to avoid error after migration
 */
function wpcs_deactivate(){

    delete_option('fs_accounts');

}

// Create a helper function for easy SDK access.
function wpcs_fs() {
    global $wpcs_fs;

    if ( ! isset( $wpcs_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $wpcs_fs = fs_dynamic_init( array(
            'id'                  => '1355',
            'slug'                => 'wp-contact-slider',
            'type'                => 'plugin',
            'public_key'          => 'pk_8c7f1aab720c6d8cbfa2c2cb0f7a0',
            'is_premium'          => false,
            'has_addons'          => true,
            'has_paid_plans'      => false,
            'menu'                => array(
                'slug'           => 'edit.php?post_type=wpcs',
                'contact'        => false,
            ),
        ) );
    }

    return $wpcs_fs;
}

// Init Freemius.
wpcs_fs();
// Signal that SDK was initiated.
do_action( 'wpcs_fs_loaded' );

