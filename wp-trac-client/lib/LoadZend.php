<?php

// load the Zend Framework.

// show error message.
//ini_set('display_errors', 1);

// hook on the plugin loaded action.
add_action('plugins_loaded', 'wptc_zend_framework_init');

/**
 * set up include path for Zend Framework.
 */
function wptc_zend_framework_init() {
    // add the lib folder into the PHP include path.
    // if there is no Zend in there.
    if (class_exists("Zend_Version")) {
        // Zend Framework is already loaded.
        // do nothing...
        // TODO: We could check the version if it is necessary.
        // Zend_Version::getLatest();
        return; 
    } else {
        set_include_path(get_include_path() . PATH_SEPARATOR . 
                         WPTC_PLUGIN_PATH . '/lib' );
        define('WP_ZEND_FRAMEWORK', true);
        wptc_zend_framework_register_autoload();
    }
}

/**
 * create a Zend auto loader instance...
 */
function wptc_zend_framework_register_autoload() {

    require_once 'Zend/Loader/Autoloader.php';
    $autoloader = Zend_Loader_Autoloader::getInstance();
}
