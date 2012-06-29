<?php
/*
Plugin Name: WP Trac Client
Plugin URI: http://www.github.com/leocornus/leocornus.tracclient
Description: An XML-RPC trac client for WordPress blogs.
Version: 0.1
Author: Leocornus Ltd.
Author URI: http://www.leocorn.com
License: GPLv2
*/

// This is the main php for our plugin.
// the header on top tells WordPress to load this plugin.

// get the WordPress database object.
global $wpdb;

// we will using wptc as the prefix for thie plugin.
// define some constants here
define('WPTC_DB', $wpdb->base_prefix . 'wptc');

// figure out the plugin path.
// this will work for symlink path too.
$my_plugin_file = __FILE__;

if (isset($plugin)) {
	$my_plugin_file = $plugin;
}
else if (isset($mu_plugin)) {
	$my_plugin_file = $mu_plugin;
}
else if (isset($network_plugin)) {
	$my_plugin_file = $network_plugin;
}

define('MY_PLUGIN_FILE', $my_plugin_file);
define('MY_PLUGIN_PATH', WP_PLUGIN_DIR.'/'.basename(dirname($my_plugin_file)));

// load the Zend Framework.

ini_set('display_errors', 1);
add_action('plugins_loaded', 'zend_framework_init');
function zend_framework_init() {
    // add the lib folder into the include path.
    set_include_path(get_include_path() . PATH_SEPARATOR . MY_PLUGIN_PATH . '/lib' );
    define('WP_ZEND_FRAMEWORK', true);
    zend_framework_register_autoload();
}

function zend_framework_register_autoload() {
    require_once 'Zend/Loader/Autoloader.php';
    $autoloader = Zend_Loader_Autoloader::getInstance();
}

// load the tmplate tags function.
require_once(MY_PLUGIN_PATH . '/tags.php');
// load ajax functions.
require_once(MY_PLUGIN_PATH . '/ajax.php');

global $wptc_client;

/**
 * register the dataTables JavaScript lib.
 * DataTables depends on jQuery.  
 * we assume jQuery is already loaded.
 */
add_action('init', 'register_resources');
function register_resources() {

    // plugins_url will check this is a ssl request or not.
    wp_register_script('jquery.dataTables',
                       plugins_url('wp-trac-client/js/jquery.dataTables.js'),
                       array('jquery'), '1.9.1');
    // using wp_enqueue_script to load this js lib where you need.
    wp_register_style('jquery.dataTables',
                      plugins_url('wp-trac-client/css/jquery.dataTables.css'));
    // using wp_enqueue_style to load this css.
    // jquery ui dialog style seens not automatically loaded.
    wp_register_style('jquery-ui',
                      'https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css');
}

function get_wptc_client() {

    // get the settings from current blog
    $rpcurl = get_blog_option(get_current_blog_id(), 'wptc_rpcurl');
    $username = get_blog_option(get_current_blog_id(), 'wptc_username');
    $password = get_blog_option(get_current_blog_id(), 'wptc_password');
    if ($rpcurl) {
        require_once 'Zend/XmlRpc/Client.php';
        $wptc_client = new Zend_XmlRpc_Client($rpcurl);
        $wptc_client->getHttpClient()->setAuth($username, $password);
    }

    return $wptc_client;
}

// we need a admin page on dashboard for configuration.
add_action('admin_menu', 'wptc_admin_init');
/**
 * the main function to set up admin page.
 */
function wptc_admin_init() {

    // add the wp-trac-client section on wp-admin dashboard.
    add_menu_page('TracClient', 'TracClient', 
                  'manage_options', // this is only for administrator
                  // menu slug, slug is like keyword.
                  'wp-trac-client/admin-settings.php', 
                  // the function name leave it empty to use the value in slug.
                  // this will also be the default option page.
                  ''  
                 );
    // the general settings page.
    add_submenu_page('wp-trac-client/admin-settings.php', // parent slug.
                     'Trac Client General Settings', 'General Settings',
                     'manage_options', 
                     'wp-trac-client/admin-settings.php'
                    );
    // some management work here.
    add_submenu_page('wp-trac-client/admin-settings.php', // parent slug.
                     'Trac Client Management', 'Manage',
                     'manage_options', 
                     'wp-trac-client/admin-manager.php'
                    );

}
?>
