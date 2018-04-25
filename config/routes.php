<?php 
	use Simple\Routing\Router;

	/**
 	 * Routes configurations.
 	 *
 	 * (@array) default - The first page that will be shown.
 	 *		(@string) controller - Controller name.
 	 *		(@string) view - View name.
	 */
	Router::configRoutes([
		'default' => ['controller' => 'Usuario', 'view' => 'login']
	]);