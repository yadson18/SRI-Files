<?php  
    namespace Simple\View\Components;
    
    class Html
    {
        private static $tags = [
            'meta' => '<meta charset="%encode%"/>',
            'link' => '<link rel="stylesheet/%type%" type="text/css" href="%href%"/>',
            'script' => '<script type="text/javascript" src="/js/%script%"></script>'
        ];

        private static $encode;

        public function encoding()
        {
            return $this->replaceProperties('meta', ['/%encode%/'], [static::$encode]);
        }

        public function css(string $cssName)
        {
            if (is_file(CSS . $cssName)) {
                return $this->replaceProperties('link', 
                    ['/%href%/', '/\/%type%/'], 
                    ['/css/' . $cssName, '']
                );
            }
    	}

        public function font(string $fontName)
        {
            if (is_file(FONT . $fontName)) {
                return $this->replaceProperties('link', 
                    ['/%href%/', '/\/%type%/'], 
                    ['/css/' . $fontName, '']
                );
            }
            return $this->replaceProperties('link', 
                ['/%href%/', '/\/%type%/'], 
                ['https://fonts.googleapis.com/css?family=' . $fontName, '']
            );
        }

        public function script(string $scriptName)
        {
            if (is_file(JS . $scriptName)) {
                return $this->replaceProperties('script', ['/%script%/'], [$scriptName]);
            }
        }

        public function less(string $lessName)
        {
            if (is_file(LESS . $lessName)) {
                return $this->replaceProperties('link', 
                    ['/%href%/', '/%type%/'], 
                    ['/less/' . $lessName, 'less']
                );
            }
        }

        protected function replaceProperties(string $tag, array $pattern, array $replacement)
        {
            if (isset(static::$tags[$tag])) {
                return preg_replace($pattern, $replacement, static::$tags[$tag]);
            }
        }

        public static function configEncode(string $encode)
        {
            static::$encode = $encode;
        }
	}