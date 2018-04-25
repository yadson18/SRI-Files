<?php 
	namespace Simple\Database\Drivers;

	use Simple\Database\Drivers\PDODriver;

	class Firebird extends PDODriver
	{
		private static $dsn = 'firebird:dbname=%host%:%path%;charset=%encode%';

		public function __construct(array $configs)
		{
			$this->setPdoConfigs($this->mountPdoConfig($configs));
		}

		protected function mountPdoConfig(array $configs)
		{
			return [
				'dsn' => preg_replace(
					['/%host%/', '/%path%/', '/%encode%/'], [
						$configs['host'], 
						$configs['path'], 
						$configs['encoding']
					], 
					static::$dsn
				),
				'user' => $configs['user'],
				'password' => $configs['password']
			];
		}
	}	