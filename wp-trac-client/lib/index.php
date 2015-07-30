<?php

//require_once WPTC_PLUGIN_PATH .
//             '/lib/Symfony/Component/ClassLoader/ClassLoader.php';

use Symfony\Component\ClassLoader\ClassLoader;

$loader = new ClassLoader();
$loader->register();

// load Wptc
$loader->addPrefix('Wptc',
                   WPTC_PLUGIN_PATH . '/lib');
// load symfony
//$loader->addPrefix('Symfony',
//                   WPTC_PLUGIN_PATH . '/lib');
