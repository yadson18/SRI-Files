<?php 
	namespace Simple\Database\Drivers;

	use PDOException;
	use PDO;

	abstract class PDODriver
	{
		private $pdoConfigs;

		public function connect()
		{
			$configs = $this->getPdoConfigs();

			if (isset($configs['dsn']) && isset($configs['user']) && 
				isset($configs['password'])
			) {
				try {
					$pdo = new PDO($configs['dsn'], $configs['user'], $configs['password']);
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

					return $pdo;
				}
				catch (PDOException $exception) {
					echo $exception->getMessage();
				}
			}
			return false;
		}

		protected function setPdoConfigs(array $configs)
		{
			$this->pdoConfigs = $configs;
		}

		protected function getPdoConfigs()
		{
			return $this->pdoConfigs;
		}
	}