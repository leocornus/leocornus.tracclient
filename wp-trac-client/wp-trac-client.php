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
global $wptc_db_version;
$wptc_db_version = "0.1";

// we will using wptc as the prefix for thie plugin.
// define some constants here
define('WPTC_DB', $wpdb->base_prefix . 'wptc');
define('WPTC_PROJECT', 'wptc_project');
define('WPTC_PROJECT_METADATA', 'wptc_project_metadata');


// figure out the plugin path.
// this will work for symlink path too.
$my_plugin_file = __FILE__;

//if (isset($plugin)) {
//	$my_plugin_file = $plugin;
//}
//else if (isset($mu_plugin)) {
//	$my_plugin_file = $mu_plugin;
//}
//else if (isset($network_plugin)) {
//	$my_plugin_file = $network_plugin;
//}

//var_dump($my_plugin_file);

define('WPTC_PLUGIN_FILE', $my_plugin_file);
define('WPTC_PLUGIN_PATH', WP_PLUGIN_DIR.'/'.basename(dirname($my_plugin_file)));

require_once(WPTC_PLUGIN_PATH . '/classes/listtables.php');
require_once(WPTC_PLUGIN_PATH . '/admin-tags.php');
require_once(WPTC_PLUGIN_PATH . '/admin-widgets.php');

// activation hook
function wptc_install() {

    wptc_logging('wptc plugin activation hook');
    global $wptc_db_version;
    wptc_logging($wptc_db_version);
    wptc_create_tables();
    add_site_option("wptc_db_version", $wptc_db_version);
}
register_activation_hook(WPTC_PLUGIN_PATH . '/' . basename(__FILE__), 'wptc_install');

// load the Zend Framework.
// show error message.
ini_set('display_errors', 1);
add_action('plugins_loaded', 'zend_framework_init');
function zend_framework_init() {
    // add the lib folder into the include path.
    set_include_path(get_include_path() . PATH_SEPARATOR . WPTC_PLUGIN_PATH . '/lib' );
    define('WP_ZEND_FRAMEWORK', true);
    zend_framework_register_autoload();
}

function zend_framework_register_autoload() {
    require_once 'Zend/Loader/Autoloader.php';
    $autoloader = Zend_Loader_Autoloader::getInstance();
}

// load the tmplate tags function.
require_once(WPTC_PLUGIN_PATH . '/tags.php');
require_once(WPTC_PLUGIN_PATH . '/widgets.php');
require_once(WPTC_PLUGIN_PATH . '/widgets/utils.php');
require_once(WPTC_PLUGIN_PATH . '/widgets/forms.php');
require_once(WPTC_PLUGIN_PATH . '/actions.php');
// load ajax functions.
require_once(WPTC_PLUGIN_PATH . '/ajax.php');
require_once(WPTC_PLUGIN_PATH . '/utils.php');
// load the WikiRenderer lib with trac wiki rule.
require_once(WPTC_PLUGIN_PATH . '/wikirenderer/WikiRenderer.lib.php');
require_once(WPTC_PLUGIN_PATH . '/wikirenderer/rules/trac_to_xhtml.php');

//global $wptc_client;

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
    // the styles and scripts for trac.
    wp_register_style('wptc-css',
                      plugins_url('wp-trac-client/css/wptc.css'));
    wp_register_script('wptc-js',
                       plugins_url('wp-trac-client/js/wptc.js'));
    // using wp_enqueue_style to load this css.
    // jquery ui dialog style seens not automatically loaded.
    wp_register_style('jquery-ui',
                      'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
    // js lib for jQuery masonry.
    wp_register_script('jquery-masonry',
                       plugins_url('wp-trac-client/js/jquery.masonry.min.js'),
                       array('jquery'), '2.1.08');
}

function get_wptc_client() {

    // get the settings from current blog
    $rpcurl = get_site_option('wptc_rpcurl');
    $username = get_site_option('wptc_username');
    $password = get_site_option('wptc_password');
    if ($rpcurl) {
        require_once 'Zend/XmlRpc/Client.php';
        $wptc_client = new Zend_XmlRpc_Client($rpcurl);
        $wptc_client->getHttpClient()->setAuth($username, $password);
    }

    return $wptc_client;
}

/**
 * retrun a rest wiki ckient.
 */
function get_wiki_client() {

    require_once 'Zend/Http/Client.php';
    $wikiApiUrl = 'http://en.wikipedia.org/w/api.php';
    $wiki_client = new Zend_Http_Client($wikiApiUrl);

    return $wiki_client;
}

function wptc_is_debug() {

    $debug = get_site_option('wptc_debug');
    return ($debug === 'true');
}

// we need a admin page on dashboard for configuration.
add_action('network_admin_menu', 'wptc_admin_init');
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
    // the template management page.
    //add_submenu_page('wp-trac-client/admin-settings.php',
    //                 'Trac Client Templates', 'Templates',
    //                 'manage_options',
    //                 'wp-trac-client/admin-templates.php'
    //                );
    // some management work here.
    add_submenu_page('wp-trac-client/admin-settings.php', // parent slug.
                     'Trac Client Management', 'Project Management',
                     'manage_options', 
                     'wp-trac-client/admin-manager.php'
                    );
    if(wptc_is_debug()) {
        add_submenu_page('wp-trac-client/admin-settings.php', // parent slug.
                         'Trac Client Testing', 'APIs Testing',
                         'manage_options', 
                         'wp-trac-client/admin-testing.php'
                        );
    }
}
?>
