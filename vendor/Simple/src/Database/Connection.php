<?php 
	namespace Simple\Database;

	use Simple\Database\Driver;
	use PDO;

	class Connection  
	{
		private $connection;

		public function __construct(string $databaseProfile)
		{	
			$this->connect(new Driver($databaseProfile));
		}

		protected function connect(Driver $driver)
		{
			$driver = $driver->getDriver();

			if (is_callable([$driver, 'connect'])) {
				$this->setConnection($driver->connect());
			}
		}

		protected function setConnection(PDO $connection){
			$this->connection = $connection;
		}

		public function getConnection(){
			return $this->connection;
		}
	}