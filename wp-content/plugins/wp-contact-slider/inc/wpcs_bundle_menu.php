<?php

/**
 * @author Mohammad Mursaleen
 * @usage function to display Addons bundle sub menu
 */

add_action('admin_menu', 'wpcs_register_bundle_submenu' , 999);
/**
 * Adds a submenu page under a custom post type parent.
 */
function wpcs_register_bundle_submenu() {
    add_submenu_page(
        'edit.php?post_type=wpcs',
        __( 'Add-Ons Bundle', 'wpcs' ),
        __( 'Add-Ons Bundle', 'wpcs' ),
        'manage_options',
        'wpcs-add-ons-bundle',
        'wpcs_add_ons_bundle_callback'
    );
}

/**
 * Display callback for the submenu page.
 */
function wpcs_add_ons_bundle_callback() {

    wp_redirect( 'https://goo.gl/Nf7T1M' );

    ?>
    <div class="wrap">
        <h1><?php _e( 'Add-ons Bundle', 'textdomain' ); ?></h1>
        <p><?php _e( 'Unleash the power of WordPress Contact Slider with Add-Ons Bundle. Get All Add-Ons at cost of just two Add-Ons and get access to all current and future Add-Ons.', 'wpcs' ); ?></p>
        <p><button id="purchase">Buy Add-ons Bundle</button>
            <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
            <script src="https://checkout.freemius.com/checkout.min.js"></script>
            <script>
                var handler = FS.Checkout.configure({
                    plugin_id:  '1936',
                    plan_id:    '2876',
                    public_key: 'pk_adaeef49092f50efdf51b91616a71',
                    image:      'https://your-plugin-site.com/logo-100x100.png'
                });

                $('#purchase').on('click', function (e) {
                    handler.open({
                        name     : 'Add Ons Bundle',
                        licenses : 1,
                        // You can consume the response for after purchase logic.
                        purchaseCompleted  : function (response) {
                            // The logic here will be executed immediately after the purchase confirmation.                                // alert(response.user.email);
                        },
                        success  : function (response) {
                            // The logic here will be executed after the customer closes the checkout, after a successful purchase.                                // alert(response.user.email);
                        }
                    });
                    e.preventDefault();
                });
            </script></p>
    </div>
    <?php
}
