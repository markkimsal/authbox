<?php

class Setup_Oauth {

	public function resources() {
		$db       = Metrodb_Connector::getHandle(_get('oauth_dsn_handle', 'default'));
		//pdo dsn is like this mysql:host=mariadb;dbname=oauth'
		$dsn      = 'mysql:host='.$db->host.';dbname='.$db->database;
		$username = $db->user;
		$password = $db->password;
		$config   = [
			'issuer'                     => 'authbox.example.com',
			'use_openid_connect'         => true,
			'allow_implicit'             => true,
			'enforce_state'              => false,
			'require_exact_redirect_uri' => false
		];
//		_didef('pdoStorageService', '\OAuth2\Storage\Pdo', array('dsn' => $dsn, 'username' => $username, 'password' => $password));
		_didef('pdoStorageService', 'oauth/dbadapter.php');
		_didef('oauthService', function() use ($config) {
				$pdo = \_make('pdoStorageService');

				$server   = new \OAuth2\Server($pdo, $config);
				$server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($pdo)); // or any grant type you like!
				$server->addGrantType(new \OAuth2\OpenID\GrantType\AuthorizationCode($pdo));
				$publicKey  = file_get_contents(_get('oauth_publickeyfile'));
				$privateKey = file_get_contents(_get('oauth_privatekeyfile'));
				// create storage
				$keyStorage = new \OAuth2\Storage\Memory(array('keys' => array(
					'public_key'  => $publicKey,
					'private_key' => $privateKey,
				)));

				$server->addStorage($keyStorage, 'public_key');
				return $server;
		});
	}
}
