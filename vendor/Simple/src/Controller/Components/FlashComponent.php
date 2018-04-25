<?php  
	namespace Simple\Controller\Components;
	
	class FlashComponent
	{
		const TEMPLATE_PATH = TEMPLATE . 'Elements' . DS . 'Flash' . DS;

		const EXT = '.php';

		private $messageType;

		private $messageText;

		public function error(string $messageText)
		{
			$this->setMessage("error", $messageText);
		}

		public function info(string $messageText)
		{
			$this->setMessage("info", $messageText);
		}

		public function success(string $messageText)
		{
			$this->setMessage("success", $messageText);
		}

		public function warning(string $messageText)
		{
			$this->setMessage("warning", $messageText);
		}

		public function showMessage()
		{
			ob_start();

			if ($this->getMessageType() && $this->getMessageText()) {
				$template = FlashComponent::TEMPLATE_PATH . $this->getMessageType() . FlashComponent::EXT;

				if (file_exists($template)) {
					$message = $this->getMessageText();

					require_once $template;

					$this->clearMessage();
				}
			}

			return ob_get_clean();
		}

		protected function setMessage(string $messageType, string $messageText)
		{
			$this->setMessageType($messageType);
			$this->setMessageText($messageText);
		}

		protected function clearMessage()
		{
			unset($this->messageType);
			unset($this->messageText);
		}

		protected function setMessageType(string $messageType){
			$this->messageType = $messageType;
		}

		protected function getMessageType(){
			return $this->messageType;
		}

		protected function setMessageText(string $messageText){
			$this->messageText = $messageText;
		}

		protected function getMessageText(){
			return $this->messageText;
		}
	}