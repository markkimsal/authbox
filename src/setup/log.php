<?php

class Setup_Log {
	public function resources($request) {
		$log    = _make('logService');

		if (_get('env') == 'dev' || _get('env') == 'demo' && ! $request->isAjax) {
			$browserHandler = new \Monolog\Handler\BrowserConsoleHandler(\Monolog\Logger::DEBUG);
			$log->pushHandler($browserHandler);
		}

		if (_get('env') == 'dev' || _get('env') == 'demo') {
			/*
			$file = new \Monolog\Handler\StreamHandler('var/log/sql.log', \Monolog\Logger::DEBUG);
//			$file->setFormatter( new \Monolog\Formatter\NormalizerFormatter() );
			$file->setFormatter( new \Monolog\Formatter\LineFormatter(null, null, true) );
			 */

			$logfile = new \Monolog\Handler\StreamHandler('var/log/info.log', \Monolog\Logger::INFO);
			$logfile->setBubble(false);
			$log->pushHandler($logfile);
		}

		$syslog = new \Monolog\Handler\StreamHandler('var/log/errors.log', \Monolog\Logger::ERROR);
		$syslog->setBubble(false);
		$log->pushHandler($syslog);

//		Metrodb_Connector::setLoggerForDsn('default', $log);
		//have to flush before fastcgi_finish_request
//		_connect('hangup', '\Monolog\Handler\BrowserConsoleHandler::send', 1);
		//have to flush before fastcgi_finish_request
		_connect('hangup', function() {
			register_shutdown_function( function() {
				\Monolog\Handler\BrowserConsoleHandler::send();
			});
		}, 3);

	}
}
