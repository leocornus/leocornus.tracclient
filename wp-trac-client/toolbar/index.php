<?php

// if this file is called directly, abort!
if(!defined('WPINC')) {
    die;
}

//add_action( 'wp_enqueue_scripts', 'load_dashicons_style' );
/**
 * Enqueue Dashicons style for frontend use
 */
//function load_dashicons_style() {
//        wp_enqueue_style( 'dashicons' );
//}

wp_register_style( 'custom-toolbar', 
                   plugins_url('custom-toolbar.css', __FILE__) );
wp_enqueue_style( 'custom-toolbar' );

require_once(WPTC_PLUGIN_PATH . '/toolbar/custom-toolbar.php');

