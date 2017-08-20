<?php

class Oauth_Userinfo {

	public $oauthService;

	/**
	 * This service provides a simple GET request to retreive a user profile.
	 */
	public function mainAction($request, $response) {
		$scope    = new \OAuth2\Scope(array(
		    'supported_scopes' => array('profile', 'email')
		));
		//$server = $this->oauthService->getServer();
		$server = $this->oauthService->__invoke();
		$server->setScopeUtil($scope);

		$userClaimsStorage = $this->makeClaimsStorage();
		$server->addStorage(
			$userClaimsStorage,
			'user_claims'
		);
		$req = \OAuth2\Request::createFromGlobals();
		$ret = $server->verifyResourceRequest($req, null, 'openid profile');
		if (!$ret) {
			$server->getResponse()->setStatusCode(401);
			$server->getResponse()->send();
			$response->statusCode = 401;
			_clearHandlers('output');
			return;
		}

		$token = $server->getAccessTokenData($req);
        $claims = $server->getStorage('user_claims')->getUserClaims($token['user_id'], $token['scope']);
        // The sub Claim MUST always be returned in the UserInfo Response.
        // http://openid.net/specs/openid-connect-core-1_0.html#UserInfoResponse
        $claims += array(
            'sub' => $token['user_id'],
        );
        $server->getResponse()->addParameters($claims);
		$server->getResponse()->send();
		_clearHandlers('output');
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
		$claims    = [];
		$dataitem  = \_makeNew('dataitem', 'account_person');
		$dataitem->hasOne('user_login', 'T0');
		$dataitem->andWhere('T0.user_login_id', (int)$user_id);
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
