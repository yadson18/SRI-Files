Configurações para o uso do framework.
	
	Instale o PHP.
		# add-apt-repository ppa:ondrej/php
		# apt-get update
		# apt-get install -y php
	
	Instale o Apache.
		# apt-get install apache2
	
	Instale um SGBD de sua preferência.
	
	Defina o diretório "webroot" como raíz do projeto nas configurações do Apache.
	
	Habilite o módulo do Apache para reescrita de URL's.
		# a2enmod rewrite
	
	Modifique o arquivo "apache.conf".
		De:
			<Directory /var/www/>
				Options Indexes FollowSymLinks
				AllowOverride None
				Require all danied
			</Directory>	

		Para:
			<Directory /var/www/>
				Options Indexes FollowSymLinks
				AllowOverride All
				Require all granted
			</Directory>	

	Instale o "php-interbase", caso for usar o "firebird".
		# apt-get install php-interbase
	
	Instale o "php-soap", caso seja necessário o uso de webservices.
		# apt-get install php-soap
	
	Por fim, reinicie o Apache, para carregar as novas configurações.
		# service apache2 restart