<?php 
	namespace App\Controller;

	class UsuarioController extends AppController
	{
		public function isAuthorized()
		{
			return $this->allow(['login']);
		}

		public function login()
		{
			if ($this->request->is('POST')) {
				$resultado = $this->Usuario->validaLogin($this->request->getData());

				if (isset($resultado->razao)) {
					return $this->redirect(['controller' => 'Nfce', 'view' => 'index']);
				}
				$this->Flash->error($resultado);
			}
			$this->setTitle('Login');
		}

		public function beforeFilter()
		{
			$this->Auth->isAuthorized([]);
		}
	}