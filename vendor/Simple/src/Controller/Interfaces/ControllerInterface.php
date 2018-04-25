<?php 
	namespace Simple\Controller\Interfaces;

	use Simple\Http\Request;
	use Simple\View\View;

	interface ControllerInterface
	{
		public function initialize(Request $request, View $view);

		public function getComponents();

		public function setTitle(string $title);
		
		public function setViewVars(array $viewVars, array $options = []);

		public function redirect($route);

		public function loadComponent(string $componentName);

		public function initializeTables();

		public function allow(array $methods);

		public function isAuthorized();

		public function beforeFilter();
	}