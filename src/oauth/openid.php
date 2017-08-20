<?php

class Oauth_Openid {

	public function configAction($request, $response) {
		$response->issuer                                = m_appurl();
		$response->authorization_endpoint                = m_appurl('authorize');
		$response->authorization_endpoint                = m_appurl('token');
		$response->userinfo_endpoint                     = m_appurl('userinfo');
		$response->revokation_endpoint                   = m_appurl('oauth/revoke');
		$response->jwks_uri                              = m_appurl('oauth/openid/certs');
		$response->response_types_supported              = [
			'code',
			'token',
			'id_token',
			'code token',
			'code id_token',
			'token id_token',
			'code token id_token',
			'none'
		];

		$response->id_token_signing_alg_values_supported = [
			"RS256"
		];

		$response->scopes_supported                      = [
			"profile",
			"email",
			"openid",
		];

		$response->token_endpoint_auth_methods_supported = [
			"client_secret_post",
			"client_secret_basic"
		];

		$response->claims_supported                      = [
			"aud",
			"email",
//			"email_verified",
			"exp",
			"family_name",
			"given_name",
			"iat",
			"iss",
			"locale",
			"name",
//			"picture",
			"sub"
		];

		$response->code_challenge_methods_supported      = [
			"plain",
			"S256"
		];

		_clearHandlers('output');
		_connect('output', array($this, 'output'));
	}

	public function output($response) {
		header('Content-type: application/json');
		//unescaped slashes is not required, just trying to match
		//google's sample
		echo json_encode($response->sectionList, JSON_UNESCAPED_SLASHES| JSON_PRETTY_PRINT);
	}
}
