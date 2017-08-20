<?php

class Setup_Config {


	public function resources($request, $response) {
		$db       = Metrodb_Connector::getHandle(_get('oauth_dsn_handle', 'default'));
		if (!isset($db->host) ) {
			//hasn't setup dsn handle for this environment
			$response->addUserMessage('No DB DSN setup for this environment.<p>Add this code to <code>etc/dsn.dev.php</code></p> <p><code>Metrodb_Connector::setDsn(\'default\', \'mysqli://dbuser:dbpass@localhost:3306/authbox_dev\');</code></p>', 'error');

		}
		$email = \_make('emailService');
		if ($email instanceof Metrodi_Proto) {

			$response->addUserMessage('No Email Service setup for this environment.<p>Add this code to <code>etc/email.dev.php</code></p> <p><code>_didef(\'emailService\',  \'email/swiftmailer.php\', [\'smtp_host\'=>\'smtp.gmail.com\', \'smtp_port\'=>465, \'smtp_security\'=>\'ssl\', \'smtp_user\'=>\'user@gmail.example.com\', \'smtp_password\'=>\'\']);</code></p>', 'error');

		}
	}
}
