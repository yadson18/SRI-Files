<?php 
	namespace Simple\View;

	use Simple\Application\Application;
	use Simple\View\Components\Html;
	use Simple\View\Components\Form;

	class View
	{
		const LAYOUT = TEMPLATE . 'Layout' . DS;

		const DEFAULT = 'default' . View::EXT;
		
		const ERROR = 'error' . View::EXT;
		
		const AJAX = 'ajax' . View::EXT;
		
		const EXT = '.php';

		private $contentType;

		private $templatePath;

		private $controllerName;

		private $template;

		private $content;

		private $title;

		private $viewVars;

		public function __construct()
		{
			$this->Html = new Html();
			$this->Form = new Form();
		}

		public function setComponents(array $components)
		{
			foreach ($components as $componentName => $instance) {
				$this->$componentName = $instance;
			}
		}

		public function fetch(string $dataIndex)
		{
			switch ($dataIndex) {
				case 'title': 
					return $this->getTitle(); 
				break;
				case 'controller': 
					return $this->getControllerName(); 
				break;
				case 'view': 
					return $this->getTemplate(); 
				break;
				case 'appName': 
					return Application::getAppName(); 
				break;
				case 'content': 
					return $this->getContent();
				break;
			}
		}

		public function setContentType(string $contentType)
		{
			$this->contentType = $contentType;
		}

		protected function getContentType()
		{
			return $this->contentType;
		}

		public function setTemplatePath(string $templatePath)
		{
			$this->templatePath = $templatePath;
		}

		protected function getTemplatePath()
		{
			return $this->templatePath;
		}

		public function setTemplate(string $template)
		{
			$this->template = $template;
		}

		protected function getTemplate()
		{
			return $this->template;
		}

		protected function getContent()
		{
			return $this->content;
		}

		protected function setContent(string $content)
		{
			$this->content = $content;
		}

		protected function renderContent(string $layout)
		{
			if (file_exists(View::LAYOUT . $layout)) {
				if (!empty($this->getViewVars())) {
					foreach ($this->getViewVars() as $variable => $value) {
						if (is_string($variable)) {
							$$variable = $value;
						}
					}
				}

				ob_start();
				
				if (file_exists(
					$this->getTemplatePath() . $this->getTemplate() . View::EXT)
				) {
					require_once $this->getTemplatePath() . $this->getTemplate() . View::EXT;
				}

				$this->setContent(ob_get_clean());

				require_once View::LAYOUT . $layout;
			}
		}

		public function render()
		{
			switch ($this->getContentType()) {
				case 'ajax':
					echo $this->renderContent(View::AJAX);
					break;
				case 'default':
					echo $this->renderContent(View::DEFAULT);

					break;
				default:
					echo $this->renderContent(View::ERROR);
					break;
			}
		}

		public function setTitle(string $title)
		{
			$this->title = $title;
		}

		protected function getTitle()
		{
			if (isset($this->title)) {
				return $this->title;
			}
			return $this->getTemplate();
		}

		public function setViewVars(array $viewVars)
		{
			$this->viewVars = serialize($viewVars);
		}

		protected function getViewVars()
		{
			return unserialize($this->viewVars);
		}

		public function setControllerName(string $controller)
		{
			$this->controllerName = $controller;
		}

		protected function getControllerName()
		{
			return $this->controllerName;
		}
	}