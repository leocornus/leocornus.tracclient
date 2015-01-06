Introduce Symfony
-----------------

Symfony_ has been a very popular PHP framework since it started
years ago.

ClassLoader
-----------

Since PHP version 5.3, PHP programming community has started to 
set up some standards for PHP classes autoloading. 
The psr-0_ standard is the first one.
Here is an example to load Solarium_ from a WordPress plugin.

**Folder Structure**

Solarium_ depends on Symfony_ components **EventDispatcher** and 
**Process**.
Here are the main files and folders::

  myplugin/
    - myplugin.php
    - lib/
      - index.php
      - Solarium/
        - Client.php
        - Core/
      - Symfony/
        - Component/
          - ClassLoader/
          - EventDispatcher/
          - Process/

**myplugin.php**

The entry point for a WordPress Plugin::

  <?php
  /**
   * Plugin Name: My Plugin for testing.
   * Version: 1.0
   */
  $plugin_file = __FILE__;
  define('MY_PLUGIN_FILE', $plugin_file);
  define('MY_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . 
         basename(dirname($plugin_file)));

  // load the 3rd party libs.
  require_once(OPSPEDIA_SEARCH_PLUGIN_PATH . '/lib/index.php');

**lib/index.php**

Here the PHP file to register class loader and load thos libs::

  <?php
  
  require_once MY_PLUGIN_PATH .
               '/lib/Symfony/Component/ClassLoader/ClassLoader.php';
  use Symfony\Component\ClassLoader\ClassLoader;
  
  $loader = new ClassLoader();
  $loader->register();
  
  // load Solarium
  $loader->addPrefix('Solarium', MY_PLUGIN_PATH . '/lib');
  // load symfony
  $loader->addPrefix('Symfony', MY_PLUGIN_PATH . '/lib');

.. _Solarium: http://github.com/solariumphp/solarium
.. _Symfony: https://github.com/symfony/symfony
.. _psr-0: http://www.php-fig.org/psr/psr-0/
.. _psr-4: http://www.php-fig.org/psr/psr-4/
.. _Implement psr-0 / psr-4 for WordPress: https://core.trac.wordpress.org/ticket/21300
.. _Autoloading in PHP and the PSR-0: http://www.sitepoint.com/autoloading-and-the-psr-0-standard/
