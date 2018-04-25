<?php 
	namespace Simple\ORM\Components;

	class Validator 
	{
		private $rules = [];

		public function addRule(string $ruleName)
		{
			$this->rules[$ruleName] = [];

			return $this;
		}

		public function notEmpty()
		{
			$this->addSubRule('null', false);

			return $this;
		}

		public function empty()
		{
			$this->addSubRule('null', true);

			return $this;
		}

		public function int()
		{
			$this->addSubRule('type', 'integer');

			return $this;
		}

		public function string()
		{
			$this->addSubRule('type', 'string');

			return $this;
		}

		public function float()
		{
			$this->addSubRule('type', 'float');

			return $this;
		}

		public function unknown()
		{
			$this->addSubRule('type', 'unknown');

			return $this;
		}

		public function size(int $size)
		{
			$this->addSubRule('size', $size);

			return $this;
		}

		public function validateRules(array $rulesAndValues)
		{
			foreach ($rulesAndValues as $column => $value) {
				if (is_string($column)) {
					if (!$this->validateRule($column, $value)) {
						return false;
					}
				}
				else {
					return false;
				}
			}
			return true;
		}

		protected function validateRule(string $ruleName, $value)
		{
			if (isset($this->rules[$ruleName])) {
				$rule = $this->rules[$ruleName];

				if (isset($rule['null']) && isset($rule['type']) && 
					isset($rule['size'])
				) {
					/*debug(
						'Coluna: ' . $ruleName .
						' / Valor: ' . $value .
						' / Tamanho: ' . strlen((string) $value) .
						' / Tamanho esperado: ' . $rule['size']
					);*/
					if (is_numeric($value) || !empty($value)) {
						if (settype($value, $rule['type'])) {
							if (strlen((string) $value) <= $rule['size']) {
								return true;
							}
						}
					}
					else if ($rule['null'] === true) {
						return true;
					}
				}
			}
			return false;
		}

		protected function addSubRule(string $subRuleName, $data)
		{
			$this->rules[$this->getLastKey()][$subRuleName] = $data;
		}

		protected function getLastKey()
		{
			end($this->rules);
            return key($this->rules);
		}
	}