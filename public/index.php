<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

define('ROOT_PATH', dirname(__DIR__));
ini_set('default_charset', 'UTF-8');

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Setup autoloading
require 'init_autoloader.php';

if ( isset($_SERVER['APPLICATION_ENV']) && ($_SERVER['APPLICATION_ENV']=='development' ) ) {
	error_reporting( E_ALL | E_STRICT );
}

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
