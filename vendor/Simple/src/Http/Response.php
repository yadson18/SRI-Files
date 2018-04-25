<?php 
	namespace Simple\Http;

	use Simple\Routing\Router;
	use Simple\Http\Request;
	use Simple\Util\Builder;
	use Simple\View\View;

	class Response
	{
		private $request;

		private $code;

		private $status;

		public function __construct(Request $request)
		{
			$this->setRequest($request);
		}

		protected function setStatus(int $code)
		{
			switch ($code) {
				case 200: $this->status = 'success'; break;
				case 301: $this->status = 'moved permanently'; break;
				case 400: $this->status = 'bad request'; break;
				case 401: $this->status = 'unauthorized'; break;
				case 403: $this->status = 'forbidden'; break;
				case 404: $this->status = 'not found'; break;
				case 500: $this->status = 'internal server error'; break;
			}
		}

		protected function getStatus()
		{
			return $this->status;
		}

		protected function setCode(int $code)
		{
			$this->setStatus($code);
			$this->code = $code;
		}

		protected function getCode()
		{
			return $this->code;
		}

		protected function setRequest(Request $request)
		{
			$this->request = $request;
		}

		protected function getRequest()
		{
			return $this->request;
		}

		public function result()
		{
			$header = $this->getRequest()->getHeader();
			$view = new View();

			if (!empty($header) && isset($header->controller)) {
				$controller = new Builder('App\\Controller\\' . $header->controller . 'Controller');
				$templatePath = TEMPLATE . $header->controller . DS;
				if ($controller->canInvokeMethod('isAuthorized') &&
					$controller->canInvokeMethod('initialize') &&
					$controller->canInvokeMethod($header->view)
				) {
					$controller->invoke('initialize', [$this->getRequest(), $view]);
				
					if ($controller->canInvokeMethod('beforeFilter')) {
						$controller->invoke('beforeFilter');
					}
					if ($controller->invoke('isAuthorized')) {
						$result = $controller->invoke($header->view, $header->args);

						if (isset($result['redirect'])) {
							Router::location($result['redirect']);
						}
						else if(is_file($templatePath . $header->view . '.php')){
							if ($controller->canUseAttribute('Ajax') &&
								call_user_func([
									$controller->useAttribute('Ajax'), 'notEmptyResponse'
								])
							) {
								$view->setContentType('ajax');
								$view->setViewVars(call_user_func([
									$controller->useAttribute('Ajax'), 'getResponse'
								]));
							}
							else {
								$view->setContentType('default');
							}

							$view->setComponents($controller->invoke('getComponents'));
							$view->setControllerName($header->controller);
							$view->setTemplatePath($templatePath);
							$view->setTemplate($header->view);	
							$this->setCode(200);
						}
						else {
							$this->setCode(404);
						}
					}
					else {
						$this->setCode(401);
					}
				}
				else {
					$this->setCode(400);
				}
			}
			else {
				$this->setCode(500);
			}

			if ($this->getCode() !== 200) {
				$view->setContentType('error');
				$view->setTemplatePath(TEMPLATE . 'Error' . DS);
			}
			return (object) [
				'code' => $this->getCode(),
				'status' => $this->getStatus(),
				'view' => $view
			];
		}
	}