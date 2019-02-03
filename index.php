<?php

define("ROOT_PATH", dirname(__DIR__));

chdir(ROOT_PATH);

if (php_sapi_name() !== 'cli') {
	echo 'To be used as cli sapi';
	return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();

