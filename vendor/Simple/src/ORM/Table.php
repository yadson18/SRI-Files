<?php
	namespace Simple\ORM;
	
	use Simple\ORM\Interfaces\EntityInterface;
	use Simple\ORM\Components\QueryBuilder;
	use Simple\Util\Builder;

	abstract class Table extends QueryBuilder
	{
		const ENTITY_NAMESPACE = 'App\\Model\\Entity\\';

		private $belongsTo = [];

		private $table;
		
		private $primaryKey;

		public function setDatabase(string $databaseProfile)
		{
			$this->configureDatabase($databaseProfile, $this->getEntityName());
		}

		public function newEntity()
		{
			$entity = new Builder($this->getEntityName());

			return $entity->getBuiltInstance();
		}

		public function patchEntity(EntityInterface $entity, array $data)
		{
			foreach ($data as $attribute => $value) {
				if (is_string($attribute)) {
					$entity->$attribute = $value;
				}
			}

			return $entity;
		}

		protected function getEntityName()
		{
			$entityName = str_replace('Table', '', splitNamespace(get_class($this)));

			return Table::ENTITY_NAMESPACE . $entityName;
		}

		protected function setBelongsTo(string $tableName, array $options)
		{
			$this->belongsTo[$tableName] = $options;
		}

		protected function getBelongsTo(string $tableName)
		{	
			if (isset($this->belongsTo[$tableName])) {
				return $this->belongsTo[$tableName];
			}
			return false;
		}

		protected function setTable(string $table)
		{
			$this->table = $table;
		}

		protected function getTable()
		{
			return $this->table;
		}

		protected function setPrimaryKey(string $primaryKey)
		{
			$this->primaryKey = $primaryKey;
		}

		protected function getPrimaryKey()
		{
			return $this->primaryKey;
		}

		public function find(array $fields)
		{
			if (!empty($this->getTable())) {
				return $this->select($fields)
					->from([$this->getTable()]);
			}
			return false;
		}

		public function get($key, array $contain = [])
		{
			if (is_numeric($key) && $key >= 0 || !empty($key)) {
				if (!empty($this->getTable())) {
					if ($key === 'all') {
						return $this->find(['*'])
							->fetch('all');
					}
					else if (!empty($this->getPrimaryKey())) {
						if (isset($contain['contain']) && !empty($contain['contain'])) {
							$condition = [
								$this->getTable() . '.' . $this->getPrimaryKey() . ' = ' => $key
							];
							$tables = [$this->getTable()];
							
							foreach ($contain['contain'] as $table) {
								$belongs = $this->getBelongsTo($table);

								if (!empty($belongs) && isset($belongs['key']) &&
									isset($belongs['foreignKey'])
								) {
									$tables[] = $table;
									$condition[] = 'and ' . 
										$this->getTable() . '.' . $belongs['key'] . ' = ' . 
										$table . '.' . $belongs['foreignKey'];

								}
							}
							return $this->select(['*'])
								->from($tables)
								->where($condition)
								->fetch('class');
						}
						return $this->find(['*'])
							->where([$this->getPrimaryKey() . ' = ' => $key])
							->fetch('class');
					}
				}
			}
			return false;
		}

		public function save(EntityInterface $entity)
		{	
			if (!empty($this->getTable())) {
				$primaryKey = $this->getPrimaryKey();

				if (isset($entity->$primaryKey)) {
					if ($this->get($entity->$primaryKey)) {
						$primaryKeyValue = $entity->$primaryKey;
						unset($entity->$primaryKey);

						return $this->update($this->getTable())
							->set((array) $entity)
							->where([$primaryKey . ' = ' => $primaryKeyValue])
							->fetch('rowCount');
					}
				}
				return $this->insert($this->getTable())
					->values((array) $entity)
					->fetch('rowCount');
			}
			return false;
		}

		public function remove(EntityInterface $entity) 
		{
			$primaryKey = $this->getPrimaryKey();

			if (isset($entity->$primaryKey) && !empty($this->getTable())) {
				return $this->delete($this->getTable())
					->where([$primaryKey . ' = ' => $entity->$primaryKey])
					->fetch('rowCount');
			}
			return false;
		}
	}