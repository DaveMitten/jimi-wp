<?php

add_action( 'wp_footer', 'wpcs_display_slider' );
/**
 * @author Mohammad Mursaleen
 * function to display WP Contact Slider on front end
 */
function wpcs_display_slider(){

    global $wp_query;
    $post_obj = $wp_query->get_queried_object();
    if ($post_obj) {
        $Page_ID = $post_obj->ID;
        $current_page_id =  (string)$Page_ID;
    }

    ///////////////////////// Code edit to display on all pages //////////// --- start ---////////////
    $args = array (
        'post_type'        => 'wpcs',
        'post_status'      => 'publish',
        'meta_query' => array(
            array(
                'key'     => 'wpcs_display_on_all',
                'value'   => 'yes',
                'compare' => '=',
            ),
        ),
    );

    // The Query
    $wpcs_query = new WP_Query( $args );

    if ( $wpcs_query->have_posts() ) { // If any slider set to Display on all pages

        wpcs_slider_section($args);
        // Restore original Post Data
        wp_reset_postdata();


        ///////////////////////// Code edit to display on all pages //////////// --- end ---////////////

    } elseif(is_home() || is_front_page()){ // added is_front_page() -- Fixed in version 1.34

        // WP_Query arguments
        $args = array(
            'post_type' => 'wpcs',
            'post_status' => 'publish',
            'meta_key' => 'wpcs_display_on_home',
            'meta_value' => 'yes',
        );

        // The Query
        $wpcs_query = new WP_Query($args);

        // The Loop
        if ($wpcs_query->have_posts()) {

            wpcs_slider_section($args);
            // Restore original Post Data
            wp_reset_postdata();
        }

    } elseif (is_page() || is_single()) {   // Added support for posts as well since version 1.2

        // WP_Query arguments
        $args = array(
            'post_type' => 'wpcs',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );

        // The Query
        $wpcs_query = new WP_Query($args);

        // The Loop
        if ($wpcs_query->have_posts()) {

            while ( $wpcs_query->have_posts() ) {

                $wpcs_query->the_post();

                // get the pages for which this slider is set
                $wpcs_pages = get_post_meta( get_the_ID() , 'wpcs_pages', false);

                // check if this page is one of selected pages
                if( in_array($current_page_id , $wpcs_pages[0])) {

                    $args['p'] = get_the_ID(); // To fix bug in version 1.34
                    wpcs_slider_section($args);

                    break;

                }
                // Restore original Post Data
                wp_reset_postdata();

            }

        }

    }

}

/**
 * @author Mohammad Murasleen
 * @usage loop to call slider
 * @param $args
 */
function wpcs_slider_section($args){

    $counter = 0;

    $wpcs_query = new WP_Query( $args );

    while ( $wpcs_query->have_posts() ) {

        $wpcs_query->the_post();

        wpcs_create_slider_slider(get_the_id());

        $counter++;

        // check to just display not more then one slider on single page
        if($counter == 1)
            break;

    }

    // Restore original Post Data
    wp_reset_postdata();

}

/**
 * @author Mohammad Mursaleen
 * @usage function to display slider content
 */
function wpcs_display_slider_content($slider_id){

    // Check which option is selected to display in slider
    $wpcs_option = get_post_meta( $slider_id , 'wpcs_option', true);

    switch ($wpcs_option) {

        case 'html':

            $wpcs_html = get_post_meta( $slider_id, 'wpcs_html', true );
            // check if the custom field has a value
            if( ! empty( $wpcs_html ) ) {
                echo apply_filters( 'wpcs_html_content' , $wpcs_html , $slider_id );
            }
            break;

        case 'shortcode':

            $wpcs_shortcode = get_post_meta( $slider_id, 'wpcs_shortcode', true );
            $wpcs_plugin_name = get_post_meta( $slider_id, 'wpcs_plugin_name', true );

            // check if the custom field has a value
            if( ! empty( $wpcs_shortcode ) ) {

                switch ($wpcs_plugin_name) {

                    case 'cf7':
                        ?>
                        <div class="wpcs-cf7">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'gf':
                        ?>
                        <div class="wpcs-gf">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'wp-form':
                        ?>
                        <div class="wpcs-wp-form">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'caldera-form':
                        ?>
                        <div class="wpcs-caldera-form">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                            <style>
                                /** WP CONTCACT SLIDER -- Style for Caldera from Date picker fix **/
                                .cfdatepicker-dropdown {z-index: 99999999999 !important;}
                            </style>
                            <script>
                                /** WP CONTCACT SLIDER -- Script for Caldera from Date picker fix **/
                                jQuery('.cfdatepicker').click(function() {
                                    setTimeout(function () {
                                        jQuery('.cfdatepicker-dropdown').each(function () {
                                            this.style.setProperty('z-index', '99999999999', 'important');
                                        });
                                    }, 100);
                                });
                            </script>
                        </div>
                        <?php

                        break;

                    case 'constant-forms':
                        ?>
                        <div class="wpcs-constant-forms">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'pirate-forms':
                        ?>
                        <div class="wpcs-pirate-forms">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'si-contact-form':
                        ?>
                        <div class="wpcs-si-contact-form">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'formidable':
                        ?>
                        <div class="wpcs-formidable">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'form-maker':
                        ?>
                        <div class="wpcs-form-maker">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'form-craft':
                        ?>
                        <div class="wpcs-form-craft">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    case 'ninja-forms':
                        ?>
                        <div class="wpcs-ninja-forms">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <style>
                            .pika-single {z-index: 99999999999 !important;}
                        </style>
                        <?php
                        break;

                    case 'visual-form-builder':
                        ?>
                        <div class="wpcs-visual-form-builder">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                    default:
                        ?>
                        <div class="wpcs-cf7">
                            <?php
                            echo do_shortcode( $wpcs_shortcode );
                            ?>
                        </div>
                        <?php
                        break;

                }

            }
            break;

        default:
            echo 'kindly select some option in your slider to display here';
    }

}





















