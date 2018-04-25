<?php 
	namespace Simple\Error;

	class ErrorHandler
	{
		private static $defaultTemplate = TEMPLATE . 'Layout' . DS . 'error.php';

		private static $templatePath = TEMPLATE . 'Error' . DS;

		private static $defaultTemplateError;

		private $templateError;

		public function showTemplate()
		{
			if (is_file($this->getTemplateToLoad())) {
				require_once $this->getTemplateToLoad();
			}
			else {
				return "<h1>Page not found.</h1>";
			}
		}

		public function display(string $errorTitle, string $template)
		{
			if (is_file($this->getDefaultTemplate())) {
				$this->setTemplateToLoad($template);

				$title = $errorTitle;

				require_once $this->getDefaultTemplate();
			}
		}

		protected function getDefaultTemplate()
		{
			return static::$defaultTemplate;
		}

		protected function setTemplateToLoad(string $template)
		{
			if ($template === 'default') {
				$this->templateError = static::$defaultTemplateError;
			}
			else {
				$this->templateError = $template;
			}
		}

		protected function getTemplateToLoad()
		{
			return static::$templatePath . $this->templateError;
		}

		public static function configPageError(string $pageError)
		{
			static::$defaultTemplateError = $pageError;
		}
	}