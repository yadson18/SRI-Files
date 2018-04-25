<?php 	
	namespace Simple\Configurator;

	abstract class PhpConfig
	{
		protected function useFunction(string $functionName, array $value)
		{
			if (function_exists($functionName)) {
				return call_user_func_array($functionName, $value);
			}
		}

		public function mbInternalEncoding(string $encode)
		{
			$this->useFunction('mb_internal_encoding', [$encode]);
		}

		public function dateDefaultTimezone(string $timezone)
		{
			$this->useFunction('date_default_timezone_set', [$timezone]);
		}

		public function defaultLocale(string $locate)
		{
			$this->useFunction('ini_set', ['intl.default_locale', $locate]);
		}

		public function displayErrors(bool $display)
		{
			$this->useFunction('ini_set', ['display_errors', $display]);
		}
	}