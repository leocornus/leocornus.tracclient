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
    set_include_path(get_include_path() . PATH_SEPARATOR . MY_PLUGIN_PATH . '/lib' );
    define('WP_ZEND_FRAMEWORK', true);
    zend_framework_register_autoload();
}

function zend_framework_register_autoload() {
    require_once 'Zend/Loader/Autoloader.php';
    $autoloader = Zend_Loader_Autoloader::getInstance();
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
