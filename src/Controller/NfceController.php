<?php 
	namespace App\Controller;

	class NfceController extends AppController
	{
		public function isAuthorized()
		{
			return $this->allow([]);
		}

		public function index($identificador = null, $pagina = 1)
		{
			$pagina = (is_numeric($pagina) && $pagina > 0) ? $pagina : 1;
			$empresa = $this->Auth->getUser('seq');
			$nfce = null;

			$this->Paginator->showPage($pagina)
				->buttonsLink('/Nfce/index/pagina/')
				->itensTotalQuantity(
					$this->Nfce->contarNotas($empresa)->quantidade
				)
				->limit(21);

			if ($identificador === 'pagina') {
				$nfce = $this->Nfce->listarNotas(
					$empresa, $this->Paginator->getListQuantity(), 
					$this->Paginator->getStartPosition()
				);
			}
			else {
				$nfce = $this->Nfce->listarNotas(
					$empresa, $this->Paginator->getListQuantity()
				);
			}
			
			$this->setTitle('Arquivos NFCe');
			$this->setViewVars([
				'nfce' => $nfce
			]);
		}

		public function download($seq = null)
		{
			if ($this->request->is('POST')) {
				$dados = $this->request->getData();

				if (isset($dados['qtd']) && is_numeric($dados['qtd']) &&
					isset($dados['seqs']) && is_array($dados['seqs'])
				) {
					$dados['qtd'] = (int) $dados['qtd'];

					if ($dados['qtd'] === 1) {
						$this->Nfce->baixarXML(array_shift($dados['seqs']));
					}
					else if ($dados['qtd'] > 1) {
						$this->Nfce->baixarZip($dados['seqs']);
						$this->Ajax->response('download', ['resposta' => 'ok']);
					}
				}
			}
			else {
				return $this->redirect('home');
			}
		}

		public function beforeFilter()
		{
			$this->Auth->isAuthorized(['index', 'download']);
		}
	}