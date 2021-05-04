<?php 
//Login


add_shortcode('wpftl-form-login', 'wpftl_form_login');

add_action('wp_enqueue_scripts', 'wpftl_enqueue_scripts');

add_action('wp_ajax_nopriv_wpftl_login','wpftl_login');
add_action('wp_ajax_wpftl_login','wpftl_login');