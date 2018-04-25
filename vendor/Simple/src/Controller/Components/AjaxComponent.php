<?php  
	namespace Simple\Controller\Components;
	
	class AjaxComponent
	{
		private $response;

		public function response(string $key, $data)
		{
			if (!empty($key) && !empty($data) && !is_resource($data)) {
				$this->response[$key] = json_encode($data, JSON_UNESCAPED_UNICODE);
			}
			else {
				$this->response = [
					'status' => 'error',
					'message' => 'Ajax response cannot be empty or resource type'
				];
			}
		}

		public function notEmptyResponse()
		{
			if (isset($this->response)) {
				return true;
			}
			return false;
		}

		public function getResponse()
		{
			return $this->response;
		}
	}
?>
