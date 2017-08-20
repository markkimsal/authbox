<?php


class Tokens_Main {

	use \Setup_Crud;
	public $tableName = 'oauth_access_tokens';

	public function _output($response) {
		$response->pageTitle = 'Tokens';
		_set('pageTitle', 'tokens');
	}
/*
	public function mainAction($request, $response) {
		$response->addInto('items', 'foobar');

		$finder = \_makeNew('dataitem', $this->getDataItemName());
		$response->addInto('items', $finder->findAsArray());
	}
 */
}
