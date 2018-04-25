<?php 
	namespace Simple\Auth;

	use Simple\ORM\Interfaces\EntityInterface;
	use Simple\Session\Session;

	abstract class Auth {
		private $session;
		private $authorizedMethods;

		public function __construct()
		{
			$this->session = new Session();
		}

		public function setUser(EntityInterface $entity)
		{
			$this->session->setData('Auth', $entity);
		}

		public function unsetUser()
		{
			$this->session->removeData('Auth');
		}	

		public function destroy()
		{
			$this->session->destroy();
		}

		public function getUser(string $index = null)
		{
			if (!empty($index)) {
				if (isset($this->session->getData('Auth')->$index)) {
					return $this->session->getData('Auth')->$index;
				}
				return false;
			}
			return $this->session->getData('Auth');
		}

		public function loginRedirect()
		{
			if (isset($this->configs['login'])) {
				return $this->configs['login'];
			}
		}

		public function logoutRedirect()
		{
			if (isset($this->configs['logout'])) {
				return $this->configs['logout'];
			}
		}

		protected function initialize(array $configs)
		{
			$this->configs = $configs;
		}

		protected function allow(array $methods = [])
		{
			$this->authorizedMethods = $methods;
		}

		public function checkAuthorization(string $method)
		{
			if (in_array($method, $this->authorizedMethods)) {
				return true;
			}
			return false;
		}
	}