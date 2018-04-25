<?php  
	namespace App\Model\Table;

	use Simple\ORM\Components\Validator;
	use Simple\ORM\Table;

	class EmpresaTable extends Table
	{
		public function initialize()
		{
			$this->setDatabase('BANCO');

			$this->setTable('EMPRESA');

			$this->setPrimaryKey('seq');

			$this->setBelongsTo('', []);
		}

		public function validaEmpresa(string $cnpj)
		{
			$empresa = $this->find(['*'])
				->where(['cnpj =' => unmask($cnpj)])
				->fetch('class');

			if ($empresa) {
				return $empresa;
			}
			return false;
		}

		protected function defaultValidator(Validator $validator)
		{
			$validator->addRule('seq')->notEmpty()->int()->size(4);
			$validator->addRule('razao')->empty()->string()->size(100);
			$validator->addRule('cnpj')->notEmpty()->string()->size(14);

			return $validator;
		}
	}