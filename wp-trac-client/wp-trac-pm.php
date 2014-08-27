<?php
/**
 * NONE Plugin Name: Trac Project Management on WordPress
 * Plugin URI: http://www.github.com/leocornus/leocornus.tracclient
 * Description: Agile Project Management at Trac on WordPress
 * Version: 0.4.3
 * Author: Sean Chen <sean.chen@leocorn.com>
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// if this file is called directly, abort!
if(!defined('WPINC')) {
    die;
}

require_once(plugin_dir_path(__FILE__) . 
             'wp-trac-pm/public/class-wp-trac-pm.php');

// register hooks for plugin activated and deactivated.
register_activation_hook(__FILE__, 
    array('WPTracProjectManagement', 'activate'));
register_deactivation_hook(__FILE__, 
    array('WPTracProjectManagement', 'deactivate'));

// loading action...
add_action('plugins_loaded', 
    array('WPTracProjectManagement', 'get_instance'));
