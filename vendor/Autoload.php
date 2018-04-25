<?php 
	abstract class Autoload
	{	
		public static function loadNamespaces()
		{
            spl_autoload_register( function($namespace) {
            	static::fromVendorDir($namespace);
            	static::fromAppDir($namespace);
            });
        }

        protected function config(string $prefix, string $baseDir, string $namespace)
        {
        	$size = strlen($prefix);

        	if (strncmp($prefix, $namespace, $size) !== 0) {
        		return;
        	}
			
			$relativeClass = substr($namespace, $size);
			
			$pathToClass = $baseDir . str_replace('\\', DS, $relativeClass) . '.php';
			
			if (file_exists($pathToClass)) {
        		require_once $pathToClass;
        	}
        }

        protected function fromVendorDir($namespace)
        {
        	static::config('Simple\\', SIMPLE, $namespace);
        }

        protected function fromAppDir($namespace)
        {
        	static::config('App\\', APP, $namespace);
        }
	}