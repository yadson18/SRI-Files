<?php 
	namespace Simple\Database\Statement;

	use Simple\ORM\Components\Validator;
	use PDOStatement;	
	use PDOException;
	use PDO;

	class Statement
	{
		private $statement;

		private $pdo;

		private $compiled = false;

		public function __construct(PDO $pdo)
		{
			$this->setPdo($pdo);
		}

		protected function compiled()
		{
			return $this->compiled;
		}

		public function compileQuery(
			string $queryType, string $query, array $values, Validator $validator
		)
		{
			if ($this->getPdo() && !empty($query) && $this->prepare($query)) {
				if (!empty($values)) {
					if ($queryType !== 'select') {
						if ($validator->validateRules($values)) {
							if ($this->bind($queryType, $values) && $this->execute()) {
								$this->compiled = true;
							}
						}
					}
					else if ($this->bind($queryType, $values) && $this->execute()) {
						$this->compiled = true;
					}
				}
				else if ($this->execute()) {
					$this->compiled = true;
				}
			}
		}

		public function rowCount()
		{
			if ($this->compiled()) {
				try {
					return $this->statement->rowCount();
				}
				catch (PDOException $exception) {
					echo $exception->getMessage();
				}
			}
			return false;
		}

		public function fetchAll()
		{
			if ($this->compiled()) {
				try {
					return $this->statement->fetchAll(PDO::FETCH_ASSOC);
				}
				catch (PDOException $exception) {
					echo $exception->getMessage();
				}
			}
			return false;
		}

		public function fetchObject($object = null)
		{
			if ($this->compiled()) {
				try {
					if (!empty($object)) {
						return $this->statement->fetchObject($object);
					}
					return $this->statement->fetchObject();
				}
				catch (PDOException $exception) {
					echo $exception->getMessage();
				}
			}
			return false;
		}

		protected function prepare(string $query)
		{
			try {
				$this->statement = $this->getPdo()->prepare($query);

				return true;
			}
			catch (PDOException $exception) {
				echo $exception->getMessage();
			}
			return false;
		}

		protected function bind(string $queryType, array $values)
		{	
			if ($this->isPdoStatement($this->statement) && !empty($values)) {
				try {
					foreach ($values as $column => $value) {
						if ($queryType === 'select' &&
							!$this->statement->bindValue(++$column, $value)
						) {
							return false;
						}
						else if ($queryType !== 'select' && 
							!$this->statement->bindValue(':' . $column, $value)
						) {
							return false;
						}
					}
					return true;
				}
				catch (PDOException $exception) {
					return $exception->getMessage();
				}
			}
			return false;
		}

		protected function execute()
		{
			if ($this->isPdoStatement($this->statement)) {
				try {
					$this->statement->execute();

					return true;
				}
				catch (PDOException $exception) {
					return $exception->getMessage();
				}
			}
			return false;
		} 

		protected function setPdo(PDO $pdo){
			$this->pdo = $pdo;
		}

		protected function getPdo(){
			return $this->pdo;
		}

		protected function isPdoStatement($statement)
		{
			if ($statement instanceof PDOStatement) {
				return true;
			}
			return false;
		}
	}