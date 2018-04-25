<?php
	namespace Simple\Application;

	use Simple\Http\Response;

	class Application
	{
		private static $appName;
		
		private $bootstrapPath;

		public function __construct(string $bootstrapPath)
		{
			$this->setBootstrapPath($bootstrapPath);
		}

		public function start(Response $response)
		{
			$result = $response->result();

			if (!empty($result)) {
				if ($result->code !== 200) {
					$result->view->setTitle('Danied Access');
					$result->view->setTemplate('daniedAccess');
				}

				$result->view->render();
			}
		}

		public function bootstrap()
		{
			if (file_exists($this->getBootstrapPath() . 'bootstrap.php')) {
				require_once $this->getBootstrapPath() . 'bootstrap.php';

				return true;
			}
			return false;
		}

		protected function setBootstrapPath(string $bootstrapPath)
		{
			$this->bootstrapPath = $bootstrapPath;
		}

		protected function getBootstrapPath()
		{
			return $this->bootstrapPath;
		}

		public static function configAppName(string $appName)
		{
			static::$appName = $appName;
		}

		public static function getAppName()
		{
			return static::$appName;
		}
	}