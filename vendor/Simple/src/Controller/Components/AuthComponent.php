<?php 
	namespace Simple\Controller\Components;

	use Simple\Auth\Auth;
	
	class AuthComponent extends Auth
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->initialize([
				'login' => [
					'controller' => 'Nfce', 
					'view' => 'index'
				],
				'logout' => [
					'controller' => 'Usuario', 
					'view' => 'login'
				]
			]);
		}

		public function isAuthorized(array $methods)
		{
			$this->allow($methods);
		}
	}