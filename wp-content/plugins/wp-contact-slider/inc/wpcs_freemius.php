<?php
/**
 * @author Mohammad Mursaleen
 * function to integrate freemius SDK
 */
function wpcs_fs() {
    global $wpcs_fs;

    if ( ! isset( $wpcs_fs ) ) {
        // Include Freemius SDK.
        require_once('freemius/start.php' );

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

function wpcs_fs1_custom_connect_message_on_update(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
) {
    return sprintf(
        __fs( 'hey-x' ) . '<br>' .
        __( 'Would be great if you can help us improve %2$s! If you are ready! some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'wp-contact-slider' ),
        $user_first_name,
        '<b>' . $plugin_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

wpcs_fs()->add_filter('connect_message_on_update', 'wpcs_fs1_custom_connect_message_on_update', 10, 6);


function wpcs_fs2_custom_connect_message_on_update(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
) {
    return sprintf(
        __fs( 'hey-x' ) . '<br>' .
        __( 'Would be great if you can help us improve %2$s! If you are ready! some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'wp-contact-slider' ),
        $user_first_name,
        '<b>' . $plugin_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

wpcs_fs()->add_filter('connect_message', 'wpcs_fs2_custom_connect_message_on_update', 10, 6);