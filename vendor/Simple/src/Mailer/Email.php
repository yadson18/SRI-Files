<?php  
	namespace Simple\Mailer;

	use Simple\Configurator\Configurator;
	use Simple\Mailer\Components\PHPMailer;

	class Email
	{
		private $host;
		private $port;
		private $email;
		private $password;
		private $security;
		private $subject;
		private $message;
		private $from;
		private $fromName;
		private $to;
		private $toName;
		private $attachment;

		public function __construct()
		{
			if (Configurator::getInstance()->get("EmailTransport")) {
				$emailConfig = Configurator::getInstance()->get("EmailTransport");

				$this->host = $emailConfig["host"];
				$this->port = $emailConfig["port"];
				$this->email = $emailConfig["email"];
				$this->password = $emailConfig["password"];
				$this->security = $emailConfig["security"];
			}
		}

		public function subject(string $subjectText)
		{
			if (!empty($subjectText)) {
				$this->subject = $subjectText;

				return $this;
			}
			return false;
		}

		public function messageTemplate(string $messageTemplate)
		{
			if (!empty($messageTemplate) && file_exists(VIEW."Email".DS.$messageTemplate)) {
				$this->message = VIEW."Email".DS.$messageTemplate;

				return $this;
			}
			return false;
		}

		public function from(string $fromEmail, string $fromName)
		{
			if (!empty($fromEmail) && !empty($fromName)) {
				$this->from = $fromEmail;
				$this->fromName = $fromName;

				return $this;
			}
			return false;
		}

		public function attachment(string $fileName)
		{
			if (!empty($fileName) && file_exists(WWW_ROOT.$fileName)) {
				$this->attachment = WWW_ROOT.$fileName;

				return $this;
			}
			return false;
		}

		public function to(string $toEmail, string $toName)
		{
			if (!empty($toEmail) && !empty($toName)) {
				$this->to = $toEmail;
				$this->toName = $toName;

				return $this;
			}
			return false;
		}

		public function send()
		{
			$Email = new PHPMailer();
			$Email->isSMTP();
			$Email->SMTPDebug = false;
			$Email->Host = $this->host;
			$Email->Port = $this->port;
			$Email->SMTPSecure = $this->security;
			$Email->SMTPAuth = true;
			$Email->Username = $this->email;
			$Email->Password = $this->password;
			$Email->setFrom($this->from, $this->fromName);
			$Email->addReplyTo($this->to, $this->toName);
			$Email->addAddress($this->to, $this->toName);
			$Email->Subject = $this->subject;
			$Email->msgHTML(file_get_contents($this->message));
			$Email->AltBody = 'This is a plain-text message body';
			
			if (!empty($this->attachment)) {
				$Email->addAttachment($this->attachment);
			}

			return $Email->send();
		}
	}