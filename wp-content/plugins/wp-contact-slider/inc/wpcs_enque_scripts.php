<?php

add_action( 'wp_enqueue_scripts', 'wpcs_enqueue_scripts' , 0 );
/**
 * @author Mohammad Mursaleen
 * function To Enqueue frontend scripts
 */
function wpcs_enqueue_scripts() {
// call jquery
    wp_enqueue_script( 'jquery' );

}
