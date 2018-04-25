<?php
	function removeNamespace($object)
	{
		return splitNamespace(get_class($object));
	}

	function arrayDeepSearch(array $keys, array $array)
	{
		while ($keys) {
			$key = array_shift($keys);

			if (array_key_exists($key, $array)) {
				return (sizeof($keys) === 0) 
					? $array[$key] 
					: arrayDeepSearch($keys, $array[$key]);
			}
		}
	}

	function mask(string $mask, string $value)
	{
		$mask = str_split($mask);
		$value = str_split($value);

		foreach ($mask as $index => $char) {
			if ($char === '#' && !empty($value)) {
				$mask[$index] = array_shift($value);
			}
		}
		return implode($mask);
	}

	function unmask(string $value)
	{
		return str_replace([
	    	'[',']','>','<','}','{',')','(',':',';','/',
	    	',','!','?','*','%','~','^','`','@','-','\\','.'
	    ], '', $value);
	}

	function minutesTo(string $conversionType, int $minutes)
	{
		switch ($conversionType) {
			case 'miliseconds':
				return (60 * $minutes) * 60;
				break;
			case 'seconds':
				return 60 * $minutes;
				break;
			case 'hours':
				return $minutes / 60;
				break;
		}
	}

	function mergeSubArrays(array $array, int $start, int $end)
	{
		$result = [];
		$array = array_values($array);
		
		if ($start < $end) {
			if (sizeof($array) > $start && sizeof($array) >= $end) {
				for ($i = $start; $i < $end; $i++) { 
					$result = array_merge($result, $array[$i]);
				}
			}
		}
		return $result;
	}

	function removeExtraSpaces(string $value)
	{
		return preg_replace('/( )+/', ' ', $value);
	}

	function splitNamespace(string $namespace)
	{
		$pieces = explode('\\', $namespace);

		if (is_array($pieces)) {
			$result = array_pop($pieces);
			return (!empty($result)) ? $result : array_pop($pieces);
		}
		return $pieces;
	}

	function findArrayValues(string $keys, array $array)
	{
		return arrayDeepSearch(explode('.', $keys), $array);
	}

	function debug($data)
	{
		echo '<pre id="debug-screen">';
		var_dump($data);
		echo '</pre>';
	}

	function sanitize($data)
	{
		return removeSpecialChars(unmask($data));
	}

	function removeSpecialChars(string $value) 
	{
		$invalid = [
			'a' => ['á','à','â','ã','ä'],
			'e' => ['é','è','ê'],
			'i' => ['í','ì'],
			'o' => ['ó','ò','ô','õ','ö'],
			'u' => ['ú','ù','ü'],
			'c' => ['ç'],
			'' => [
	    		'[',']','>','<','}','{',';','#',
	    		'!','?','*','%','~','^','`','@'
	    	]
		];

		foreach ($invalid as $replaceTo => $invalidValues) {
			$value = str_replace($invalidValues, $replaceTo, $value);
			$value = str_replace(
				array_map('mb_strtoupper', $invalidValues), strtoupper($replaceTo), $value
			);
		}
		return $value;
	}