<?php 
	namespace Simple\Controller;

	use Simple\Controller\Interfaces\ControllerInterface;
	use Simple\Controller\Component;
	use Simple\ORM\TableRegistry;
	use Simple\Session\Session;
	use Simple\Routing\Router;
	use Simple\Http\Request;
	use Simple\View\View;

	abstract class Controller implements ControllerInterface
	{
		const NAMESPACE = 'App\\Controller\\';

		const SUFIX = 'Controller';

		public $request;

		private $view;

		private $components = [];

		public function initialize(Request $request, View $view)
		{
			$this->request = $request;
			
			$this->view = $view;

			$this->initializeTables();
		}

		public function getComponents()
		{
			return $this->components;
		}

		public function setTitle(string $title)
		{
			$this->view->setTitle($title);
		}

		public function setViewVars(array $viewVars, array $options = [])
		{
			/*if (!empty($options)) {
				$session = new Session();

				foreach ($options as $variable => $value) {
					if (is_string($variable)) {
						# code...
					}
				}

				debug($session);
				exit;
			}*/
			$this->view->setViewVars($viewVars);
		}

		public function redirect($route)
        {
        	if (is_array($route)) {
        		if (isset($route['controller']) && !empty($route['controller'])) {
	        		if (!isset($route['view']) || empty($route['view'])) {
	        			$route['view'] = 'index';
					}
					
	        		return ['redirect' => implode('/', $route)];
	        	}
        	}
        	else if (is_string($route)) {
        		return ['redirect' => implode('/', Router::getRoute($route))];
        	}
        	return false;
		}

		public function loadComponent(string $componentName)
		{
			$component = new Component();

			if ($component->register($componentName)) {
				$this->components[$componentName] = $component->getComponent();
				$this->$componentName = $component->getComponent();
			}
		}

		public function initializeTables()
		{
			$tableName = str_replace('Controller', '', removeNamespace($this));
			$table = TableRegistry::get($tableName);

			if (!empty($table)) {
				$this->$tableName = $table;
			}
		}
	}