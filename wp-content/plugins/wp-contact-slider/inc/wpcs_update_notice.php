<?php

add_action('admin_footer','wpcs_notice_script');
/**
 * @author Mohammad Mursaleen
 * @usage script to run ajax request on closing notice
 */
function wpcs_notice_script(){
    if (  get_option('wpcs_display_notice_2_1') == 'no' ){
        return '';
    }
    ?>
    <script>
        jQuery(document).on( 'click', '.wpcs-first-notice .notice-dismiss', function() {
            jQuery.ajax({
                url: ajaxurl,
                data: {
                    action: 'dismiss_wpcs_notice'
                }
            })
        })
    </script>
    <style>
        .wpcs-first-notice {
            padding: 0;
        }
        .wp-6-c {
            width: 49%;
            display: inline-block;
        }
        .wp-6-c img {
            width: auto;
            height: 128px;
            float: left;
            margin-bottom: -4px;
        }
        .content-contact {
            float: left;
            width: 74%;
        }
        .logo-contact {
            float: left;
        }
        .content-contact h2 {
            padding: 0!important;
            font-size: 16px;
            margin: 7px 0 0 0!important;
            line-height: 24px;
            padding-left: 14px!important;
        }
        .content-contact p {
            line-height: 16px;
            margin: 0;
            padding-left: 16px;
        }
        .content-contact .button.button-primary.button-hero {
            box-shadow: 0 2px 0 #006799;
            margin: 8px 0 0px 16px;
            height: auto;
            line-height: 34px;
        }
        .wpcs-first-notice > h2 {
            position: absolute;
            top: -61px;
            display: none;
        }
        @media only screen and (max-width: 1252px) {

            .content-contact .button.button-primary.button-hero {
                margin: 3px 0 0px 16px;
            }
            .wp-6-c img {
                height: 100px;
            }
            .content-contact p {
                margin: 5px 0 0 0;
            }
            .wp_contact_notice {
                margin-top: 65px;
            }
            .wpcs-first-notice > h2 {
                display: block;
            }
            .content-contact h2{
                display: none;
            }
        }
        @media only screen and (max-width: 1024px) {
            .wp-6-c {
                width: 100%;
            }
        }
    </style>
    <?php
}


add_action( 'admin_notices', 'wpcs_update_notice' );
/**
 * @author Mohammad Mursaleen
 * @usage display update notice
 */
function wpcs_update_notice() {

    if (  get_option('wpcs_display_notice_2_1') == 'no' )
        return;

    if( isset($_GET['page']) && 'wp-contact-slider-addons' == $_GET['page']){
        update_option('wpcs_display_notice_2_1','no');
        return;
    }


    $addons_url = admin_url( 'edit.php?post_type=wpcs&page=wp-contact-slider-addons', 'https' );

    $class = 'notice notice-info is-dismissible wpcs-first-notice';
    $heading = __( 'Introducing NEW Add Ons For WP Contact Slider' , 'wpcs' );
    $message = __( '<div class="wp_contact_notice">
                    <div class="wp-6-c">
                    <div class="logo-contact">
                          <a href="'.$addons_url.'"> <img src="'. plugins_url( 'img/notice-images/contact-slider.png', dirname(__FILE__) ). '" alt=""/></a>
                    </div>
                    <div class="content-contact">
                    <h2>Introducing NEW Add Ons For WP Contact Slider</h2>
                    <p>After many feature requests and to support the free version,
                    Releasing Ultimate addons collection for you.</p>
                    <a class="button button-primary button-hero" href="'.$addons_url.'">Checkout NEW Add Ons</a>
                    </div>
                    </div>
                    <div class="wp-6-c">
                       <a href="'.$addons_url.'"> <img src="'. plugins_url( 'img/notice-images/advance-setting-icon.png', dirname(__FILE__) ). '" alt=""/></a>
                          <a href="'.$addons_url.'"> <img src="'. plugins_url( 'img/notice-images/trigers-and-shortcodes.png', dirname(__FILE__) ). '" alt=""/></a>
                          <a href="'.$addons_url.'"> <img src="'. plugins_url( 'img/notice-images/font-awesome-icon.png', dirname(__FILE__) ). '" alt=""/></a>
                          <a href="'.$addons_url.'"> <img src="'. plugins_url( 'img/notice-images/multiple-sliders.png', dirname(__FILE__) ). '" alt=""/></a>
                    </div>
                    </div>' , 'wpcs');

    printf( '<div data-dismissible="notice-one-forever-wpcs" class="%1$s"><h2 style="font-size: 20px;font-weight: 800;" >%2$s</h2>%3$s</div>', esc_attr( $class ), esc_html( $heading ) ,  $message  );

}


/**
 * @author : Mohammad Mursaleen
 * @usage : save in option to not display it again
 */
function wpcs_dismiss_update_notice(){

    update_option('wpcs_display_notice_2_1','no');

}
add_action('wp_ajax_dismiss_wpcs_notice', 'wpcs_dismiss_update_notice');