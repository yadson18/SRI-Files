<?php 
	namespace Simple\Controller; 

	use ReflectionClass;

	class Component
	{
		const NAMESPACE = 'Simple\\Controller\\Components\\';

		const SUFIX = 'Component';

		private $component;

		public function register(string $componentName)
		{
			$componentName = Component::NAMESPACE . $componentName . Component::SUFIX;

			if ($this->componentExists($componentName)) {
				$component = new ReflectionClass($componentName);
				$this->component = $component->newInstance();

				return true;
			}
			$this->component = null;
			
			return false;
		}

		public function getComponent()
		{
			return $this->component;
		}

		protected function componentExists(string $componentName)
		{
			if (class_exists($componentName)) {
				return true;
			}
			return false;
		}
	}