<?php

class Oauth_Token {

	public $oauthService;

	public function mainAction($request) {
		$server = $this->oauthService->__invoke();

		$ret = $server->handleTokenRequest(OAuth2\Request::createFromGlobals());//->send();
		$ret->send();
		_clearHandlers('output');
	}
}
