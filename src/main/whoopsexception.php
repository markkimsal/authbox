<?php
class Main_Whoopsexception {

	public function oldexception() {
		$run     = new Whoops\Run;
		$handler = new Whoops\Handler\PrettyPageHandler;

		// Set the title of the error page:
		$handler->setPageTitle("errorWhoops! There was a problem.");
		$response = _make('response');
		$handler->addDataTable('template vars', $response->sectionList);

		$run->pushHandler($handler);
		$run->handleException( _get('last_exception') );
	}

	public function exception($request) {
		$run     = new Whoops\Run;
		if ($request->isAjax) {
			$handler = new Whoops\Handler\JsonResponseHandler;
		} else {
			$handler = new Whoops\Handler\PrettyPageHandler;
			$handler->setPageTitle("errorWhoops! There was a problem.");
			// Set the title of the error page:
			$response = _make('response');
			$handler->addDataTable('template vars', $response->sectionList);
		}

		$run->pushHandler($handler);
		$run->handleException( _get('last_exception') );
	}

}
