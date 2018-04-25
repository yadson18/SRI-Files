<?php 
	namespace Simple\Configurator;

	use Simple\Configurator\PhpConfig;

	class Configurator extends PhpConfig
	{
		private static $Instance;

		private $appConfigs = [];

		private function __construct(){}

		public static function getInstance()
		{
			if (!isset(self::$Instance)) {
				self::$Instance = new Configurator();
			}
			return self::$Instance;
		}

		public function set(string $configName, $config)
		{
			if (!empty($configName) && !is_numeric($configName)) {
				if (is_bool($config) || !empty($config)) {
					$this->appConfigs[$configName] = $config;
				
					return $this;
				}
			}
			return false;
		}

		public function use(string $configName)
		{
			return findArrayValues($configName, $this->appConfigs);
		}
	}