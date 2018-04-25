<?php 
	namespace Simple\ORM;

	use Simple\Util\Builder;

	abstract class TableRegistry
	{
		const NAMESPACE = 'App\\Model\\Table\\'; 

		const SUFIX = 'Table';

		public static function get(string $tableName)
		{
			return static::getTableInstance($tableName);
		}

		protected function getTableInstance(string $tableName)
		{
			$table = new Builder(TableRegistry::NAMESPACE . $tableName . TableRegistry::SUFIX);

			if ($table->canInvokeMethod('initialize')) {
				$table->invoke('initialize');

				return $table->getBuiltInstance();
            }
		}
	}