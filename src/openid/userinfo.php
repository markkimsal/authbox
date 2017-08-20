<?php

class Oauth_Userinfo {

	public function mainAction($request) {
		$dsn      = 'mysql:host=mariadb;dbname=oauth';
		$username = 'docker';
		$password = 'mysql';
		$storage  = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

		$config   = [
			'issuer'=>'authbox.example.com',
			'use_openid_connect' => true
		];

		$server   = new OAuth2\Server($storage, $config);
		$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); // or any grant type you like!
		$server->addGrantType(new OAuth2\OpenID\GrantType\AuthorizationCode($storage));

		$scope    = new \OAuth2\Scope(array(
		    'supported_scopes' => array('profile')
		));
		$server->setScopeUtil($scope);

		$publicKey  = file_get_contents('selfsignwithus_root_public.key');
		$privateKey = file_get_contents('selfsignwithus_root_private.key');
		// create storage
		$keyStorage = new OAuth2\Storage\Memory(array('keys' => array(
			'public_key'  => $publicKey,
			'private_key' => $privateKey,
		)));

		$server->addStorage($keyStorage, 'public_key');

		$userClaimsStorage = $this->makeClaimsStorage();
		$server->addStorage(
			$userClaimsStorage,
			'user_claims'
		);
		$req = OAuth2\Request::createFromGlobals();
		$req->server['REQUEST_METHOD']  = 'POST';
		$ret = $server->handleUserInfoRequest($req);//->send();
		$ret->send();
		exit();
	}

	public function makeClaimsStorage() {

		return new UserClaimsStorage();

	}
}

class UserClaimsStorage implements \OAuth2\OpenID\Storage\UserClaimsInterface {

	/**
	 * PROFILE_CLAIM_VALUES  = 'name family_name given_name middle_name nickname preferred_username profile picture website gender birthdate zoneinfo locale updated_at';
	 */
	public function getUserClaims($user_id, $scope) {
		$scopeList = explode(' ', $scope);
		$claims = [];
		$dataitem = \_makeNew('dataitem', 'account_person');
		$dataitem->andWhere('user_login_id', (int)$user_id);
		$x = $dataitem->find();
		if (!count($x)) {
			return $claims;
		}
		$account = $x[0];

		if (in_array('profile', $scopeList)) {
			$claims['name']       = $account->get('firstname') . ' ' .$account->get('lastname');
			$claims['zoneinfo']   = $account->get('tzone');
			$claims['locale']     = $account->get('locale');
			$claims['updated_at'] = $account->get('updated_on');
		}

		$dataitem = \_makeNew('dataitem', 'user_login');
		$dataitem->andWhere('user_login_id', (int)$user_id);
		$x = $dataitem->find();
		if (!count($x)) {
			$claims;
		}
		$login = $x[0];

		if (in_array('email', $scopeList)) {
			$claims['email'] = $login->get('email');
		}
		return $claims;
	}
}
