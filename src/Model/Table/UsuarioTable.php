<?php  
	namespace App\Model\Table;

	use Simple\ORM\Components\Validator;
	use Simple\ORM\TableRegistry;
	use Simple\ORM\Table;

	class UsuarioTable extends Table
	{
		public function initialize()
		{
			$this->setDatabase('BANCO');

			$this->setTable('USUARIO');

			$this->setPrimaryKey('login');

			$this->setBelongsTo('', []);
		}

		public function validaLogin(array $dados)
		{
			$empresa = TableRegistry::get('Empresa');

			if (isset($dados['cnpj']) && isset($dados['login']) && 
				isset($dados['senha'])
			) {
				$empresa = $empresa->validaEmpresa($dados['cnpj']);

				if ($empresa) {
					$empresa->usuario = $this->find(['seq', 'nome'])
						->where([
							'empresa =' => $empresa->seq, 'and',
							'login =' => $dados['login'], 'and',
							'senha =' => $dados['senha']
						])
						->fetch('class');

					if ($empresa->usuario) {
						return $empresa;
					}
					return 'Usuário não encontrado, login ou senha incorretos, tente novamente.';
				}
				return 'Nenhuma empresa foi encontrada com o CNPJ digitado.';
			}
			return 'Os campos CNPJ, login e senha, são obrigatórios.';
		}

		protected function defaultValidator(Validator $validator)
		{
			$validator->addRule('seq')->notEmpty()->int()->size(4);
			$validator->addRule('empresa')->notEmpty()->int()->size(4);
			$validator->addRule('nome')->notEmpty()->string()->size(100);
			$validator->addRule('login')->notEmpty()->string()->size(15);
			$validator->addRule('senha')->notEmpty()->string()->size(15);
			$validator->addRule('email')->empty()->string()->size(30);

			return $validator;
		}
	}