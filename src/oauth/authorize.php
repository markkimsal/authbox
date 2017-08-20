<?php

class Oauth_Authorize {

	public $oauthService;

	/**
	 * Show form
	 */
	public function mainAction($request, $response, $user) {
		$response->set('state', $request->cleanString('state'));
		//load the client
		$cid = $request->cleanString('cid');
		if (!$cid) {
			$cid = $request->cleanString('client_id');
		}

		//TODO: is state required? add a flag to check
		/*
		$state = $request->cleanString('state');
		if (!$state) {
			$response->addUserMessage('Missing authorization parameters: state', 'error');
		}
		 */

		$req      = \OAuth2\Request::createFromGlobals();
		$resp     = new \OAuth2\Response();
		$server = $this->oauthService->__invoke();
		if (!$server->validateAuthorizeRequest($req, $resp)) {
			$params = $resp->getParameters();
			if (isset($params['error_description'])) {
				$response->addUserMessage($params['error_description'], 'error');
			} else {
				$response->addUserMessage('Unknown error', 'error');
			}
			_set('template.main.file', 'error');
			return;
		}

		$scope         = $request->cleanString('scope');
		$redir         = $request->cleanString('redirect_uri');
		$response_type = $request->cleanString('response_type');

		$dataitem = \_makeNew('dataitem', 'oauth_clients');
		$dataitem->andWhere('client_id', $cid);
		$dataitem->loadExisting();

		$response->set('client',        $dataitem);
//		$response->set('state',         $state);
		$response->set('scope',         $scope);
		$response->set('scopeList',     explode(' ', $scope));
		$response->set('redirect_uri',  $redir);
		$response->set('response_type', $response_type);
	}

	public function verifyAction($request, $response) {

		$auth     = $request->cleanBool('auth');
		$req      = \OAuth2\Request::createFromGlobals();
		$resp     = new \OAuth2\Response();

		$scope    = new \OAuth2\Scope(array(
		    'supported_scopes' => array('profile', 'email')
		));
		//$server = $this->oauthService->getServer();
		$server = $this->oauthService->__invoke();
//		$server->setScopeUtil($scope);

		if (!$server->validateAuthorizeRequest($req, $resp)) {
		    $resp->send();
			_clearHandlers('output');
		}

		$user = \_make('user');
		$userid = $user->userId;
		$server->handleAuthorizeRequest($req, $resp, $auth, $userid);

		$resp->send();
		_clearHandlers('output');
	}
}
