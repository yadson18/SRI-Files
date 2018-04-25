<?php 
	namespace Simple\Database; 

	use Simple\Util\Builder;

	class Driver
	{
		const NAMESPACE = 'Simple\\Database\\Drivers\\';

		private static $driversConfigs;

		private $driver;

		public function __construct(string $databaseProfile)
		{
			$configs = $this->getDriverConfig($databaseProfile);

			if ($configs && isset($configs['driver'])) {
				$driver = $configs['driver'];

				$this->setDriver($driver, $configs);
			}
		}

		protected function setDriver(string $driverName, array $configs)
		{
			$driver = new Builder(Driver::NAMESPACE . $driverName, [$configs]);

			$this->driver = $driver->getBuiltInstance();
		}

		public function getDriver()
		{
			return $this->driver;
		}

		protected function getDriverConfig(string $databaseProfile)
		{
			if (isset(static::$driversConfigs[$databaseProfile])) {
				return static::$driversConfigs[$databaseProfile];
			}
		}

		public static function configDrivers(array $driversConfigs)
		{
			static::$driversConfigs = $driversConfigs;
		}
	}