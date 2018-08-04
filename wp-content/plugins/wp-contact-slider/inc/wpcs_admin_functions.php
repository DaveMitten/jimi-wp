<?php

/**
 * @author Mohammad Mursaleen
 * function to Register Custom Post Type for contact slider
 */
if ( ! function_exists('wpcs_post_type') ) {

    function wpcs_post_type() {

        $labels = array(

            'name'                => _x( 'WP Contact Sliders', 'Post Type General Name', 'wpcs' ),

            'singular_name'       => _x( 'WP Contact Slider', 'Post Type Singular Name', 'wpcs' ),

            'menu_name'           => __( 'WP Contact Slider', 'wpcs' ),

            'parent_item_colon'   => __( '', 'wpcs' ),

            'all_items'           => __( 'All Sliders', 'wpcs' ),

            'view_item'           => __( 'View Contact Slider', 'wpcs' ),

            'add_new_item'        => __( 'Add New Contact Slider', 'wpcs' ),

            'add_new'             => __( 'Add New Contact Slider', 'wpcs' ),

            'edit_item'           => __( 'Edit Contact Slider', 'wpcs' ),

            'update_item'         => __( 'Update Contact Slider', 'wpcs' ),

            'search_items'        => __( 'Search Contact Slider', 'wpcs' ),

            'not_found'           => __( 'Not found', 'wpcs' ),

            'not_found_in_trash'  => __( 'Not found in Trash', 'wpcs' ),

        );

        $args = array(

            'label'               => __( 'wpcs_post_type', 'wpcs' ),

            'description'         => __( 'For WP Contact Slider', 'wpcs' ),

            'labels'              => $labels,

            'supports'            => array( 'title' ),

            'hierarchical'        => true,

            'public'              => true,

            'show_ui'             => true,

            'show_in_menu'        => true,

            'show_in_nav_menus'   => true,

            'menu_icon'           => plugins_url( 'img/icon2.png', dirname(__FILE__) ),

            'show_in_admin_bar'   => true,

            'menu_position'       => 10,

            'can_export'          => true,

            'has_archive'         => false,

            'exclude_from_search' => true,

            'publicly_queryable'  => true,

            'capability_type'     => 'page',

            'menu_position' => 10

        );

        register_post_type( 'wpcs', $args );

    }

    // Hook into the 'init' action

    add_action( 'init', 'wpcs_post_type', 0 );

}


add_filter('get_sample_permalink_html', 'wpcs_remove_permalink_meta', '',4);
/**
 * @author Mohammad Mursaleen
 * function to remove permalink meta
 */
function wpcs_remove_permalink_meta($return, $id, $new_title, $new_slug){
    global $post;

    if($post->post_type == 'wpcs'){
        $ret2 = "";
        return $ret2;
    } else {
        return $return;
    }

}


add_filter( 'pre_get_shortlink', 'wpcs_remove_shortlink_button', 10, 2 );
/**
 * @author Mohammad Mursaleen
 * function to remove get short link button
 */
function wpcs_remove_shortlink_button( $false, $post_id ){

    return 'wpcs' === get_post_type( $post_id ) ? '' : $false;

}


add_filter( 'gettext', 'wpcs_change_publish_text', 10, 2 );
/**
 * @author Mohammad Mursaleen
 * function to change publish button text
 */
function wpcs_change_publish_text( $translation, $text ) {

    if (  'wpcs' == get_post_type()){

        add_action( 'admin_print_footer_scripts', 'wpcs_admin_footer_scripts' );

        if ( $text == 'Publish' )
            return 'Save';

    }

    return $translation;

}


/**
 * @author Mohammad Mursaleen
 * function to enque admin footer scripts
 */
function wpcs_admin_footer_scripts(){

?>
<script>

    jQuery(document).ready(function($){$('.rwmb-add-file').hide();});

    jQuery(document).ready(function($){
            $('#shortcode').hide();
            $('#sales-reprensentatives').hide();
            $('#text').hide();

    });

/*********   Script to manage meta boxes dynamically *********/

    jQuery(document).ready(function(){

        <?php
        $args = array (
            'post_type'        => 'wpcs',
            'post_status'      => 'publish',
            'meta_key'         => 'wpcs_display_on_home',
            'meta_value'       => 'yes',
        );

        $check_if_some_post_is_set_for_home = get_posts( $args );

        $home_sliders = get_posts( $args );

        foreach($home_sliders as $home_slider){

            $home_slider_id = $home_slider->ID;

        }

        if( $check_if_some_post_is_set_for_home && ( !(isset($_GET['post'])) ) ){
         ?>
        jQuery('.wpcs_display_on_home').hide();
		jQuery('.wpcs_display_on_home').after('<font style="color:#81d742;"><?php _e('You have already choosen a slider to display on homepage.','wpcs'); ?></font><br><br>');
        <?php }
         elseif($check_if_some_post_is_set_for_home && ( ($_GET['post'] != $home_slider_id ) ) ){
         ?>
        jQuery('.wpcs_display_on_home').hide();
		jQuery('.wpcs_display_on_home').after('<font style="color:#81d742;"><?php _e('You have already choosen a slider to display on homepage.','wpcs'); ?></font><br><br>');
        <?php } ?>

        var $value = jQuery('input[name=wpcs_option]:checked', '#post').val();

        switch ($value) {

            case 'shortcode':
                jQuery('#text').fadeOut(200);
                jQuery('#shortcode').fadeIn(900);

                break;

            case 'html':
                jQuery('#shortcode').fadeOut(200);
                jQuery('#text').fadeIn(900);

                break;

            default :
                jQuery('#shortcode').hide();
                jQuery('#text').hide();

                break;
        }

    });

    jQuery('#post input').on('change', function() {

        var $value = jQuery('input[name=wpcs_option]:checked', '#post').val();

        if( $value == 'shortcode'){
            jQuery('#text').fadeOut(200);
            jQuery('#shortcode').fadeIn(900);
        }

        if( $value == 'html' ){
            jQuery('#shortcode').fadeOut(200);
            jQuery('#text').fadeIn(900);
        }

    });
</script><?php

}


add_action( 'do_meta_boxes', 'wpcs_remove_revolution_slider_meta_boxes' );
/**
 * @author Mohammad Mursaleen
 * function to avoid confliction with Revolution slider
 */
function wpcs_remove_revolution_slider_meta_boxes() {
    remove_meta_box( 'mymetabox_revslider_0', 'wpcs', 'normal' );
}


add_action('post_submitbox_minor_actions' , 'wpcs_customize_publish_box');
/**
 * @author Mohammad Mursaleen
 * @param $post
 */
function wpcs_customize_publish_box($post){

    if( 'wpcs' == $post->post_type ){

        $style = "<style>#minor-publishing-actions,#misc-publishing-actions{display: none !important;}</style>";
        echo $style;
    }

}
