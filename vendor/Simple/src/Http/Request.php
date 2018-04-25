<?php 
	namespace Simple\Http;

	use Simple\Routing\Router;
	use Simple\Http\Response;
	use stdClass;

	class Request
	{
		private $requestMethod;

		private $controller;

		private $view;

		private $data;

		private $args;

		public function __construct(string $url, string $requestMethod)
		{
			$this->setHeader($url, $requestMethod);
		}

		protected function setHeader(string $url, string $requestMethod)
		{
			$requestArgs = explode("/", substr($url, 1));
			$controller = (string) array_shift($requestArgs);
			$view = (string) array_shift($requestArgs);
			
			$route = new Router($controller, $view);
			$this->setMethod($requestMethod);
			$this->setController($route->getController());
			$this->setView($route->getView());
			$this->setArgs($requestArgs);

			if ($requestMethod === 'GET' || $requestMethod === 'POST' || 
				$requestMethod === 'PUT' || $requestMethod === 'DELETE'
			) {
            	$this->setData($_REQUEST);
			}
		}

		public function getHeader()
		{
			return (object) [
				'requestMethod' => $this->getMethod(),
				'controller' => $this->getController(),
				'view' => $this->getView(),
				'requestData' => $this->getData(),
				'args' => $this->getArgs()
			];
		}

		public function send()
		{
			return new Response($this);
		}

		protected function setMethod(string $requestMethod){
			$this->requestMethod = $requestMethod;
		}

		protected function getMethod(){
			return $this->requestMethod;
		}

		protected function setController(string $controller){
			$this->controller = $controller;
		}

		protected function getController(){
			return $this->controller;
		}

		protected function setView(string $view){
			$this->view = $view;
		}

		protected function getView(){
			return $this->view;
		}

		protected function setData(array $data){
			$this->data = $data;
		}

		public function getData(){
			return $this->data;
		}

		protected function setArgs(array $args){
			$this->args = $args;
		}

		public function getArgs(){
			return $this->args;
		}

		public function is(string $requestMethod)
		{
			if ($this->getMethod() === $requestMethod) {
				return true;
			}
			return false;
		}
	}