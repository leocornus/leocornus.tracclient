<?php
/*
Plugin Name: WP Trac Client
Plugin URI: http://www.github.com/leocornus/leocornus.tracclient
Description: An XML-RPC trac client for WordPress blogs.
Version: 0.4.2
Author: Sean Chen <sean.chen@leocorn.com>
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

require_once(WPTC_PLUGIN_PATH . '/admin/index.php');
require_once(WPTC_PLUGIN_PATH . '/admin/init.php');
require_once(WPTC_PLUGIN_PATH . '/admin-tags.php');
require_once(WPTC_PLUGIN_PATH . '/admin-widgets.php');

// activation hook has to be in main php file.
function wptc_install() {

    wptc_logging('wptc plugin activation hook');
    global $wptc_db_version;
    wptc_logging($wptc_db_version);
    wptc_create_tables();
    wptc_create_pages();
    add_site_option("wptc_db_version", $wptc_db_version);
}
register_activation_hook(WPTC_PLUGIN_PATH . '/' . basename(__FILE__), 'wptc_install');

// try to load the Zend lib.
require_once(WPTC_PLUGIN_PATH . '/lib/LoadZend.php');
// load the tmplate tags function.
require_once(WPTC_PLUGIN_PATH . '/tags.php');
require_once(WPTC_PLUGIN_PATH . '/widgets.php');
require_once(WPTC_PLUGIN_PATH . '/widgets/index.php');
require_once(WPTC_PLUGIN_PATH . '/actions.php');
// load ajax functions.
require_once(WPTC_PLUGIN_PATH . '/ajax.php');
require_once(WPTC_PLUGIN_PATH . '/json/index.php');
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
    wp_register_script('wptc-projects',
                    plugins_url('wp-trac-client/js/wptc-projects.js'),
                       array('jquery'), '1.0.0');
    // using wp_enqueue_style to load this css.
    // jquery ui dialog style seens not automatically loaded.
    wp_register_style('jquery-ui',
                      'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
    // js lib for jQuery masonry.
    wp_register_script('jquery-masonry',
                       plugins_url('wp-trac-client/js/jquery.masonry.min.js'),
                       array('jquery'), '2.1.08');

    // RESOURCES for AngularJS
    wp_register_script('wptc-angularjs-core',
        plugins_url('wp-trac-client/js/angular.min.js'),
        array('jquery'), '1.2.23');
    wp_register_script('wptc-angularjs-route',
        plugins_url('wp-trac-client/js/angular-route.min.js'),
        array('wptc-angularjs-core'), '1.2.23');
    wp_register_script('wptc-angularjs-resource',
        plugins_url('wp-trac-client/js/angular-resource.min.js'),
        array('wptc-angularjs-core'), '1.2.23');
    wp_register_script('wptc-angularfire',
        plugins_url('wp-trac-client/js/angularfire.min.js'),
        array('wptc-angularjs-core'), '0.8.0');
    wp_register_script('wptc-firebase',
        plugins_url('wp-trac-client/js/firebase.js'),
        array('wptc-angularjs-core'), '1.0.18');
    // bootstrap js.
    wp_register_script('wptc-bootstrap-js',
        plugins_url('wp-trac-client/js/bootstrap.js'),
        array('jquery'), '3.3.5');

    // Resources for d3
    wp_register_script('wptc-d3',
        plugins_url('wp-trac-client/js/d3.v3.min.js'),
        array(), '3.4.12');

    wp_register_style('wptc-bootstrap', 
        plugins_url('wp-trac-client/css/bootstrap.css'),
        array(), '3.3.5');
    wp_register_style('wptc-bootstrap-theme', 
        plugins_url('wp-trac-client/css/bootstrap-theme.css'),
        array('wptc-bootstrap'), '3.3.5');
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

    $debug = get_site_option('wptc_debug', 'false');
    return ($debug === 'true');
}
