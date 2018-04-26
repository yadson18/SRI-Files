<?php  
	namespace App\Model\Table;

	use Simple\ORM\Components\Validator;
	use Simple\ORM\Table;

	class NfceTable extends Table
	{
		public function initialize()
		{
			$this->setDatabase('BANCO');

			$this->setTable('NFCE');

			$this->setPrimaryKey('seq');

			$this->setBelongsTo('', []);
		}

		public function listarNotas(int $empresa, int $quantity = null, int $skipTo = null)
		{
			$nfce = $this->find([
				'seq', 'chave', 'modelo', 'serie', 'numero_nf',
        		"case cancelado
           			when 'I' then 'Inutilizado'
           			when 'F' then 'Autorizado'
           			when 'T' then 'Cancelado'
        		end as STATUS",
   				'data_aut as data_evento', 'hora_aut as hora_evento',
   				'data_rec as data_backup', 'hora_rec as hora_backup'
			]);

			if (!empty($quantity)) {
				$nfce->limit($quantity);
			}
			if (!empty($skipTo)) {
				$nfce->skip($skipTo);
			}
				
			return $nfce->orderBy(['serie', 'numero_nf'])
				->where(['empresa =' => $empresa])
				->fetch('all');
		}

		public function baixarArquivoUnico(int $seq)
		{
			$nfce = $this->get($seq);
			/*$caminho = preg_split('/[\\\\\/]/i', $nfce->caminho);
			$arquivoNome = array_pop($caminho);*/

			if ($nfce) {
			   	header('Content-Description: File Transfer');
			    header('Content-Disposition: attachment; filename="' . $nfce->numero_nf . '.php"');
			    header('Content-Type: application/octet-stream');
			    header('Content-Transfer-Encoding: binary');
			    header('Content-Length: ' . filesize('index.php'));
			    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			    header('Pragma: public');
			    header('Expires: 0');
			   
			    readfile('index.php');
			}
		}

		public function contarNotas(int $empresa)
		{
			return $this->find([])
				->count('numero_nf')->as('quantidade')
				->where(['empresa =' => $empresa])
				->fetch('class');
		}

		protected function defaultValidator(Validator $validator)
		{
			$validator->addRule('seq')->notEmpty()->int()->size(4);
			$validator->addRule('empresa')->notEmpty()->int()->size(4);
			$validator->addRule('chave')->empty()->string()->size(44);
			$validator->addRule('modelo')->notEmpty()->int()->size(4);
			$validator->addRule('serie')->notEmpty()->int()->size(2);
			$validator->addRule('numero_nf')->notEmpty()->int()->size(4);
			$validator->addRule('valor')->notEmpty()->int()->size(8);
			$validator->addRule('cancelado')->notEmpty()->string()->size(1);
			$validator->addRule('data_aut')->notEmpty()->string()->size(4);
			$validator->addRule('hora_aut')->notEmpty()->string()->size(4);
			$validator->addRule('data_rec')->notEmpty()->string()->size(4);
			$validator->addRule('hora_rec')->notEmpty()->string()->size(4);
			$validator->addRule('caminho')->notEmpty()->string()->size(1024);
			$validator->addRule('aplicativo')->empty()->string()->size(50);

			return $validator;
		}
	}