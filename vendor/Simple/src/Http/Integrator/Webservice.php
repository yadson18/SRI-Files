<?php
    namespace Simple\Http\Integrator;
    
    use SoapClient;

    class Webservice
    {
        private static $instance;
        private static $configurations;
        private $connection;

        private function __construct(){}

        public function connect()
        {
            if (isset(static::$configurations['url']) &&
                isset(static::$configurations['configs'])
            ) {
                try {
                    $this->connection = new SoapClient(
                        static::$configurations['url'], static::$configurations['configs']
                    );

                    if (isset($this->connection)) {
                        return true;
                    }
                } 
                catch (Exception $Exception) {
                    return false;
                }
            }
            return false;
        }
        
        public static function getInstance()
        {
            if (!isset(static::$instance)) {
                static::$instance = new Webservice();
            }
            return static::$instance;
        }

        public function callFunction(string $functionName, $arguments)
        {
            if (is_callable([$this->connection, $functionName])) {
                return call_user_func_array(
                    [$this->connection, $functionName], [$arguments]
                );
            }
            return false;
        }

        public static function configOptions(array $configs)
        {
            static::$configurations = $configs;
        }
    }