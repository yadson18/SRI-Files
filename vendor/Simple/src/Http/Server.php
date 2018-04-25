<?php 
	namespace Simple\Http;

	use Simple\Application\Application;
	use Simple\Http\Request;

	class Server
	{
		private $app;

		private $request;

		public function __construct(Application $app)
		{
			$this->setApp($app);
		}

		public function run(bool $serverStatus)
		{
			if ($serverStatus) {
				$this->getApp()->start($this->getRequest()->send());
			}
		}

		public function listening()
		{
			if ($this->getApp()->bootstrap()) {
				$this->setRequest(new Request($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']));

				return true;
			};
			return false;
		}

		protected function setApp(Application $app)
		{
			$this->app = $app;

			return $this;
		}

		protected function getApp()
		{
			return $this->app;
		}

		protected function getRequest()
		{
			return $this->request;
		}

		protected function setRequest(Request $request)
		{
			$this->request = $request;
		}
	}