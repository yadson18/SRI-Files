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
					$this->Auth->setUser($resultado);
					
					return $this->redirect($this->Auth->loginRedirect());
				}
				$this->Flash->error($resultado);
			}
			$this->setTitle('Login');
		}

		public function logout()
		{
			$this->Auth->destroy();
			return $this->redirect($this->Auth->logoutRedirect());
		}

		public function beforeFilter()
		{
			$this->Auth->isAuthorized(['logout']);
		}
	}