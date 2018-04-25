<?php
	/**
	 * Constants to simplify system directories access
	 */
	
	define('DS', DIRECTORY_SEPARATOR);

	// Directory root.
	define('ROOT', dirname(__DIR__));

	define('APP_DIR', 'src');
	
	// Directory /src.
	define('APP', ROOT . DS . APP_DIR . DS);

	// Directory /src/Controller.
	define('CONTROLLER', APP . 'Controller' . DS);

	// Directory /src/Model.
	define('MODEL', APP . 'Model' . DS);

	// Directory /src/Template.
	define('TEMPLATE', APP . 'Template' . DS);

	// Directory /config.
	define('CONFIG', ROOT . DS . 'config' . DS);

	// Directory /vendor.
	define('VENDOR', ROOT . DS . 'vendor' . DS);

	// Directory /vendor/Simple.
	define('SIMPLE_PATH', VENDOR . 'Simple' . DS);

	// Directory /vendor/Simple/src.
	define('SIMPLE', SIMPLE_PATH . 'src' . DS);

	// Directory webroot.
	define('WWW_ROOT', ROOT . DS . 'webroot' . DS);

	// Directory /webroot/css.
	define('CSS', WWW_ROOT . 'css' . DS);

	// Directory /webroot/js.
	define('JS', WWW_ROOT . 'js' . DS);

	// Directory /webroot/less.
	define('LESS', WWW_ROOT . 'less' . DS);

	// Directory /webroot/webfonts.
	define('FONT', WWW_ROOT . 'webfonts' . DS);