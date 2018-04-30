<?php 
	/**
	 * System configurations.
	 */

	require_once 'routes.php';
	require_once APP . 'functions.php';

	use Simple\Configurator\Configurator;

	$config = Configurator::getInstance();

	$config

		/**
		 * App configurations.
		 *
		 * (@string) name - The application name.
		 * (@string) encoding - Application default encoding.
		 * (@string) timezone - Application default time zone.
		 * (@string) locate - Application default locate.
		 * (@boolean) displayErrors - 
		 *		(true) System will shown errors and warnings.
		 *		(false) Errors or warnings turn off.
		 */
		->set('App', [
			'name' => 'SRI Notas', 	 
			'encoding' => 'utf-8',	 
			'timezone' => 'UTC',	 
			'locate' => 'pt-Br',		 
			'displayErrors' => true
		])

		/**
		 * Email configurations.
		 * 
		 * (@array) default - Default email configuration.
		 * 		(@string) host - Email host.
		 *		(@int) port - The email host port.
		 *		(@string) security - The email host protocol.
		 *		(@string) email - Your email.
		 *		(@string) password - Your email password.
		 */ 
		->set('Email', [
			'default' => [
				'host' => 'smtp.gmail.com',		 
				'port' => 587,					 
				'security' => 'tls',			 
				'email' => 'example@email.com', 
				'password' => 'secret'			 
			]
		])

		/**
		 * Databases configurations.
		 * 
		 * (@array) default - Default database configuration.
		 *		(@string) driver - The database driver name.
		 *		(@string) host - Database host.
		 *		(@string) path - The database file path.
		 *		(@string) user - Database user name;
		 *		(@string) password - Database user password.
		 *		(@string) encoding - The database encoding.
		 */
		->set('Databases', [
			'BANCO' => [
				'driver' => 'Firebird',
				'host' => 'localhost',		 
				'path' => 'C:\DB\BANCO.FDB',  
				'user' => 'SYSDBA',
				'password' => 'masterkey',
				'encoding' => 'utf-8'
			]
		])

		/**
		 * Session configurations.
		 * 
		 * (@int) cookieLifeTime - Session cookie duration.
		 * (@int) regenerateId - Time to regenerate session id.
		 */
		->set('Session', [
			'cookieLifeTime' => minutesTo('seconds', 1440),
			'regenerateId' => minutesTo('seconds', 20)
		])

		/**
		 * Webservice configurations.
		 *
		 * (@string) url - The webservice URL.
		 * (@array) configs - Webservice configurations. 
		 */
		->set('Webservice', [
			'url' => "http://examplews.com/soap", 
			'configs' => [
				'soap_version' => 'SOAP_1_2',
                'exceptions' => true,
                'trace' => 1,
                'cache_wsdl' => 'WSDL_CACHE_NONE',
                'stream_context' => stream_context_create([
                	'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT
                    ]
                ])
			]
		]);	