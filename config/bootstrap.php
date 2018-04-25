<?php 
	/**
	 * Verifying if PHP version are equal or higher than 5.6.0.
	 */
	if (version_compare(PHP_VERSION, '5.6.0') < 0) {
	    trigger_error('PHP version must be equal or higher than 5.6.0.', E_USER_ERROR);
	}
	
	/**
	 * Verifying if intl extension is enabled.
	 */
	if (!extension_loaded('intl')) {
	    trigger_error('You must enable the intl extension.', E_USER_ERROR);
	}
	
	/**
	 * Verifying if intl extension is enabled.
	 */
	if (!extension_loaded('mbstring')) {
	    trigger_error('You must enable the mbstring extension.', E_USER_ERROR);
	}

	require_once SIMPLE . 'internal-functions.php';
	require_once CONFIG . 'app-config.php';

	/**
	 * Inserting application default configurations.
	 */
	$config->dateDefaultTimezone($config->use('App.timezone'));
	$config->mbInternalEncoding($config->use('App.encoding'));
	$config->displayErrors($config->use('App.displayErrors'));
	$config->defaultLocale($config->use('App.locate'));

	use Simple\Http\Integrator\Webservice;
	use Simple\Application\Application;
	use Simple\View\Components\Html;
	use Simple\Database\Driver; 
	use Simple\Session\Session;

	Application::configAppName($config->use('App.name'));
	Webservice::configOptions($config->use('Webservice'));
	Driver::configDrivers($config->use('Databases'));
	Html::configEncode($config->use('App.encoding'));
	Session::configSession($config->use('Session'));