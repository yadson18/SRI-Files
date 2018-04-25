<?php  
	namespace App\Model\Table;

	use Simple\ORM\Components\Validator;
	use Simple\ORM\Table;

	class %table_name%Table extends Table
	{
		public function initialize()
		{
			$this->setDatabase('%db_profile%');

			$this->setTable('%table%');

			$this->setPrimaryKey('%primary_key%');

			$this->setBelongsTo('', []);
		}

		protected function defaultValidator(Validator $validator)
		{%table_attributes%

			return $validator;
		}
	}