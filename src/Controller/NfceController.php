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
				->limit(6);

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

			if ($this->request->is('GET')) {
				if (is_numeric($seq)) {
					$this->Nfce->baixarArquivoUnico($seq);
				}
			}
			/*if ($this->request->is('POST')) {
				$dados = $this->request->getData();

				if (isset($dados['quantidade']) && is_numeric($dados['quantidade']) &&
					isset($dados['seq']) && is_numeric($dados['seq'])
				) {
					if ($dados['quantidade'] === 1 && is_numeric($dados['seq'])) {
						$this->Nfce->baixarArquivoUnico($dados['seq']);
					}
					else if($dados['quantidade'] > 1) {
						return true;
					}
					else {
						return true;
					}
				}
			}*/
		}

		public function beforeFilter()
		{
			$this->Auth->isAuthorized(['index', 'download']);
		}
	}