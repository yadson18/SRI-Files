<?php 
	namespace Simple\ORM\Components;

	use Simple\Database\Statement\Statement;
	use Simple\ORM\Components\Validator;
	use Simple\Database\Connection;
	
	class QueryBuilder
	{
		private $entity;

        private $connection;

		private $type = 'select';

		private $lastQueryPartIndex = [];

		private $parts = [
	        'select' => [
	        	'first' => [],
	        	'skip' => [],
	        	'distinct' => [],
	        	'sum' => [],
	        	'count' => [],
	        	'fields' => [],
	        	'from' => [],
	        	'join' => [],
	        	'where' => [],
	        	'group' => [],
	        	'order' => []
	        ],
	        'insert' => [
	        	'into' => [],
	        	'fields' => [],
	        	'values' => []
	        ],
	        'delete' => [
	        	'from' => [],
	        	'where' => []
	        ],
	        'update' => [
	        	'table' => [],
	        	'set' => [],
	        	'where' => []
	        ]
	    ];

		private $values = [];

		public function configureDatabase(string $databaseProfile, string $entity)
		{
			$this->setConnection(new Connection($databaseProfile));
			$this->setEntity($entity);
		}

		public function select(array $fieldsToGet = [])
		{
			$this->setQueryType('select');
			if (!empty($fieldsToGet)) {
				$this->insertQuery('fields', 0, implode(', ', $fieldsToGet));
			}

			return $this;
		}

		public function as(string $alias)
		{
			$column = key($this->lastQueryPartIndex);
			$index = array_shift($this->lastQueryPartIndex);

			if (isset($this->parts['select'][$column][$index])) {
				$newAlias = $this->parts['select'][$column][$index] . ' as ' . $alias;
				$this->insertQuery($column, $index, $newAlias);
			}

			return $this;
		}

		public function first(int $quantity)
		{
			$this->insertQuery('first', 0, 'first ' . $quantity);

			return $this;
		}

		public function skip(int $skipTo)
		{
			$this->insertQuery('skip', 0, 'skip ' . $skipTo);

			return $this;
		}

		public function limit(int $limit)
		{
			$this->first($limit);

			return $this;
		}

		public function distinct(string $fieldToDistinct)
		{
			$this->insertQuery('distinct', 0, 'distinct(' . $fieldToDistinct . ')');
			$this->lastQueryPartIndex['distinct'] = 0;
				
			return $this;
		}

		public function sum(string $fieldToSum)
		{
			$this->insertQuery('sum', '', 'sum(' . $fieldToSum . ')');	
			$this->lastQueryPartIndex['sum'] = sizeof($this->parts['select']['sum']) - 1;

			return $this;
		}

		public function count(string $fieldToCount)
		{
			$this->insertQuery('count', '', 'count(' . $fieldToCount . ')');	
			$this->lastQueryPartIndex['count'] = sizeof($this->parts['select']['count']) - 1;

			return $this;
		}

		public function from(array $tables)
		{
			$this->insertQuery('from', 0, 'from ' . implode(', ', $tables));

			return $this;
		}

		public function join(array $join)
		{
			$this->parts['select']['join'] = array_merge(
				$this->parts['select']['join'], $join
			);

			return $this;
		}

		public function where(array $queryCondition)
		{
			$condition = 'where';

			while ($queryCondition) {
				$column = key($queryCondition);
				$value = array_shift($queryCondition);

				if (is_string($column)) {
					if ($this->getQueryType() === 'select') {
						$condition .= ' ' . $column . ' ?'; 
						$this->values[] = $value;
					}
					else {
						$columnRemoveSignal = strstr($column, ' ', true);
						
						$condition .= ' ' . $column . ' :' . $columnRemoveSignal;
						$this->values[$columnRemoveSignal] = $value;
					}
				}
				else {
					$condition .= ' ' . $value;
				}
			}

			$this->insertQuery('where', 0, $condition);

			return $this;
		}

		public function groupBy(array $fieldsToGroup)
		{
			$this->insertQuery('group', 0, 'group by ' . implode(', ', $fieldsToGroup));

			return $this;
		}
		
		public function orderBy(array $fieldsToOrder)
		{
			$this->insertQuery('order', 0, 'order by ' . implode(', ', $fieldsToOrder));

			return $this;
		}

		public function insert(string $table)
		{
			$this->setQueryType('insert');
			$this->insertQuery('into', 0, 'into ' . $table);

			return $this;
		}

		public function values(array $dataToInsert)
		{
			$fields = implode(', ', array_keys($dataToInsert));
			$values = ':' . implode(', :', array_keys($dataToInsert));

			while ($dataToInsert) {
				$column = key($dataToInsert);
				$value = array_shift($dataToInsert);

				if (is_string($column)) {
					$this->values[$column] = $value;
				}
			}

			$this->insertQuery('fields', 0, '(' . $fields . ')');
			$this->insertQuery('values', 0, 'values(' . $values . ')');

			return $this;
		}

		public function delete(string $table)
		{
			$this->setQueryType('delete');
			$this->from([$table]);

			return $this;
		}

		public function update(string $table)
		{
			$this->setQueryType('update');
			$this->insertQuery('table', 0, $table);

			return $this;
		}

		public function set(array $fieldsToUpdate)
		{
			$fields = '';

			while ($fieldsToUpdate) {
				$column = key($fieldsToUpdate);
				$value = array_shift($fieldsToUpdate);

				if (is_string($column)) {
					$fields .= ', ' . $column . ' = :' . $column;
					$this->values[$column] = $value; 
				}
			}

			$this->insertQuery('set', 0, 'set ' . substr($fields, 2));

			return $this;
		}

		public function fetch(string $fetchType = null)
		{
			$validator = $this->defaultValidator(new Validator());
			$queryType = $this->getQueryType();
			$query = $this->queryToString();
			$values = $this->getQueryValues();
			$this->resetToDefault();

			$statement = new Statement($this->getConnection());
			$statement->compileQuery($queryType, $query, $values, $validator);

			switch ($queryType) {
				case 'select':
					switch ($fetchType) {
						case 'all':
							return $statement->fetchAll();
							break;
						case 'object':
							return $statement->fetchObject();
							break;
						case 'class':
							if (class_exists($this->getEntity())) {
								return $statement->fetchObject($this->getEntity());
							}
							break;
						default:
							return 'Undefined fetch type.';
							break;
					}
					break;
				default:
					switch ($fetchType) {
						case 'rowCount':
							return $statement->rowCount();
							break;
						default:
							return 'Undefined fetch type.';
							break;
					}
					break;
			}
			
			return false;
		}

		protected function setConnection(Connection $connection)
		{
			$this->connection = $connection->getConnection();
		}

		protected function getConnection()
		{
			return $this->connection;
		}

		protected function setEntity(string $entity)
		{
			$this->entity = $entity;
		}

		protected function getEntity()
		{
			return $this->entity;
		}

		protected function queryToString()
		{
			$queryType = $this->getQueryType();
			$queryString = $queryType . ' ';

			switch ($queryType) {
				case 'select':
					$queryString .= ' ' . implode(' ', mergeSubArrays(
						$this->parts[$queryType], 0, 2
					));
					$queryString .= ' ' . implode(', ', mergeSubArrays(
						$this->parts[$queryType], 2, 6
					));
					$queryString .= ' ' . implode(', ', mergeSubArrays(
						$this->parts[$queryType], 6, 7
					));
					$queryString .= ' ' . implode(' ', mergeSubArrays(
						$this->parts[$queryType], 7, sizeof($this->parts[$queryType])
					));
					break;
				default:
					$queryString .= ' ' . implode(' ', mergeSubArrays(
						$this->parts[$queryType], 0, sizeof($this->parts[$queryType])
					));
					break;
			}

			return removeExtraSpaces($queryString);
		}

		protected function getQueryValues()
		{
			return $this->values;
		}

		protected function resetToDefault()
		{
			$this->parts = [
	        	'select' => [
	        		'first' => [], 'skip' => [], 'distinct' => [], 'sum' => [], 
	        		'count' => [], 'fields' => [], 'from' => [], 'join' => [], 
	        		'where' => [], 'group' => [], 'order' => []
	        	],
	        	'insert' => ['into' => [], 'fields' => [], 'values' => []],
	        	'delete' => ['from' => [], 'where' => []],
	        	'update' => ['table' => [], 'set' => [], 'where' => []]
	    	];
			$this->values = [];
		}

		protected function insertQuery(string $partsIndex, $subIndex, string $query)
		{
			if ($subIndex === '') {
				$this->parts[$this->getQueryType()][$partsIndex][] = $query;
			}
			else {
				$this->parts[$this->getQueryType()][$partsIndex][$subIndex] = $query;
			}
		}

		protected function setQueryType(string $type)
		{
			$this->type = $type;
		}

		protected function getQueryType()
		{
			return $this->type;
		}
	}