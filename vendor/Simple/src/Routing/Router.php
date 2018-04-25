<?php 
	namespace Simple\Routing;  

	class Router
	{
		private static $routes; 

		private $controller;

		private $view = 'index';

		public function __construct(string $controller, string $view)
		{
			$this->setRoute(['controller' => $controller, 'view' => $view]);
		}

		protected function setController(string $controller)
		{
			$this->controller = ucfirst($controller);
		}

		public function getController()
		{
			return $this->controller;
		}

		protected function setView(string $view)
		{
			$this->view = $view;
		}

		public function getView()
		{
			return $this->view;
		}

		public function setRoute(array $route)
		{
			if (isset($route['controller']) && isset($route['view'])) {
				if (empty($route['controller'])) {
					if (static::getRoute('default.controller') && 
						static::getRoute('default.view')
					) {
						$this->setController(static::getRoute('default.controller'));
						$this->setView(static::getRoute('default.view'));
					}
				}
				else {
					if (!empty($route['view'])) {
						$this->setView($route['view']);
					}
					
					$this->setController($route['controller']);
				}
			}
		}

		public static function location(string $route)
		{
			if (!empty($route)) {
				if (static::getRoute($route . '.controller')) {
					if (!static::getRoute($route . '.view')) {
						header('Location: /' . static::getRoute($route . '.controller') . '/index');
					}
					else {
						header('Location: /' . implode('/', Router::getRoute($route)));
					}
				}
				else {
					header('Location: /' . $route);
				}
			}
		}

		public static function configRoutes(array $routes)
		{
			static::$routes = $routes;
		}

		public static function getRoute(string $routeName)
		{
			return findArrayValues($routeName, static::$routes);
		}
	}