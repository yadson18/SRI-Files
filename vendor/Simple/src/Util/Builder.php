<?php 
	namespace Simple\Util;

	use ReflectionClass;
	use ReflectionMethod;

	class Builder
	{
		private $className;

		private $classInstance;

		public function __construct(string $className, array $args = [])
		{
			$this->build($className, $args);
		}

		protected function setBuiltInstance($classInstance)
		{
			$this->classInstance = $classInstance;
		}

		public function getBuiltInstance()
		{
			return $this->classInstance;
		}

		public function invoke(string $methodName, $args = [])
		{
			if ($this->canInvokeMethod($methodName)) {
				$method = new ReflectionMethod($this->getClassName(), $methodName);
				
				return $method->invokeArgs($this->getBuiltInstance(), $args);
			}
			return false;
		}

		public function canInvokeMethod(string $methodName)
		{
			if (is_callable([$this->getBuiltInstance(), $methodName])) {
				return true;
			}
			return false;
		}

		public function canUseAttribute(string $attributeName)
		{
			if (isset($this->getBuiltInstance()->$attributeName)) {
				return true;
			}
			return false;
		}

		public function useAttribute(string $attributeName)
		{
			if ($this->canUseAttribute($attributeName)) {
				return $this->getBuiltInstance()->$attributeName;
			}
			return false;
		}

		protected function canBuild(string $className)
		{
			if (class_exists($className)) {
				return true;
			}
			return false;
		}

		protected function build(string $className, array $args = [])
		{
			if ($this->canBuild($className)) {
				$class = new ReflectionClass($className);

				$this->setClassName($className);
				$this->setBuiltInstance($class->newInstanceArgs($args));
			}
		}

		protected function setClassName(string $className)
		{
			$this->className = $className;
		}

		protected function getClassName()
		{
			return $this->className;
		}
	}