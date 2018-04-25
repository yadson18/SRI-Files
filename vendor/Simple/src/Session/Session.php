<?php 
	namespace Simple\Session;  
	
	class Session
	{
		private static $sessionConfigs;

		public function __construct()
		{
			$this->start();
		}

		public function setData(string $index, $data)
		{
			$this->regenerateId();

			if ($this->sessionCreated() && !empty($index)) {
				$_SESSION['newSession']['data'][$index] = serialize($data);
			}
		}

		public function getData(string $index)
		{
			if ($this->sessionCreated() &&
				isset($_SESSION['newSession']['data'][$index])
			) {
				return unserialize($_SESSION['newSession']['data'][$index]);
			}
		}

		public function removeData(string $index)
		{
			if ($this->getData($index)) {
				unset($_SESSION['newSession']['data'][$index]);
			}
		}

		public function destroy()
		{
			if ($this->sessionCreated()) {
				session_destroy();
			}
		}

		protected function sessionCreated()
		{
			if (isset($_SESSION['newSession'])) {
				return true;
			}
			return false;
		}

		protected function start()
		{
			if (!$this->sessionCreated() && 
				isset(static::$sessionConfigs['cookieLifeTime'])
			) {
				session_start([
					'cookie_lifetime' => static::$sessionConfigs['cookieLifeTime']
				]);
				
				if (!$this->sessionCreated()) {
					$_SESSION = [
						'newSession' => [
							'data' => [],
							'created' => date('H:i')
						]
					];
				}
			}
		}

		protected function regenerateId()
		{
			if ($this->sessionCreated() &&
				isset(static::$sessionConfigs['regenerateId'])
			) {
				$currentTime = strtotime(date('H:i'));
				$sessionTimeCreated = strtotime($_SESSION['newSession']['created']);
				$time = static::$sessionConfigs['regenerateId'];
				
				if (($currentTime - $sessionTimeCreated) >= $time) {
					session_regenerate_id(); 
					$_SESSION['newSession']['created'] = date('H:i');
				}
			}
		}

		public static function configSession(array $configs)
		{
			static::$sessionConfigs = $configs;
		}
	}