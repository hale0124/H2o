<?php
date_default_timezone_set('Europe/Prague');

define('PUBLIC_DIR', __DIR__);
define('ROOT_DIR', __DIR__.'/../');

define('VERSION','1.0.0 beta1');

error_reporting(E_ALL); 
ini_set('display_errors', '1');

define('REQUEST_MICROTIME', microtime(true));
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
