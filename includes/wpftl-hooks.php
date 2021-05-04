<?php 
//Login shortcode
add_shortcode('wpftl-form-login', 'wpftl_form_login');

add_action('wpftl-content-form-login','wpftl_content_form_login',10);

add_action('wp_enqueue_scripts', 'wpftl_enqueue_scripts');

//Login
add_action('wp_ajax_nopriv_wpftl_login','wpftl_login');
add_action('wp_ajax_wpftl_login','wpftl_login');

//Logout
add_action( 'wp_ajax_wpftl_logout', 'wpftl_logout' );
add_action( 'wp_ajax_nopriv_wpftl_logout', 'wpftl_logout' );