<?php 
	namespace App\Controller;

	class %controller_name%Controller extends AppController
	{
		public function isAuthorized()
		{
			return $this->allow([]);
		}

		public function beforeFilter()
		{
			$this->Auth->isAuthorized([]);
		}
	}