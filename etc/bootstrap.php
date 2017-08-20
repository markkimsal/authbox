<?php
//default to develop environment
//this connects with $request->isDevelopment() $request->isDemo() $request->isEnv('prod')
_set('env', 'dev');
//override with Apache SetEnv
// or fastcgi_param   APPLICATION_ENV  production
if (array_key_exists('APPLICATION_ENV', $_SERVER)) {
	_set('env', $_SERVER['APPLICATION_ENV']);
}
if (array_key_exists('APP_ENV', $_SERVER)) {
	_set('env', $_SERVER['APP_ENV']);
}

//setup metrofw
_iCanHandle('analyze',   'metrofw/analyzer.php');
_iCanHandle('analyze',   'metrofw/router.php', 3);
_iCanHandle('resources', 'metrofw/output.php');
_iCanHandle('output',    'metrofw/output.php');
//will be removed if output.php doesn't think we need HTML output
_iCanHandle('output',    'metrofw/template.php', 3);


_connect('template',          'template/smarty.php::template', 3);
_connect('template.main',     'template/smarty.php::template', 3);

_connect('template.sparkmsg', 'template/sparkmsg.php::template');

#raintpl
#_iCanHandle('template.main',    'template/rain.php::template', 3);

//_connect('exception', 'metrofw/exdump.php::onException');
if (_get('env') == 'dev') {
	_connect('exception', 'main/whoopsexception.php');
}
_iCanHandle('hangup',    'metrofw/output.php');

_didef('request',        'metrofw/request.php');
_didef('response',       'metrofw/response.php');
_didef('router',         'metrofw/router.php');
_didef('foobar',         (object)array());

_didef('logService',  '\Monolog\Logger', 'webapp');
_connect('resources', 'setup/log.php');
_connect('resources', 'setup/oauth.php');
_didef('crud', 'setup/crud.php');
_make('crud');


_set('oauth_dsn_handle',     'default');
_set('oauth_publickeyfile',  'etc/ssl/testclient_root_public.key');
_set('oauth_privatekeyfile', 'etc/ssl/testclient_root_private.key');


//metrodb
_didef('dataitem', 'metrodb/dataitem.php');
@include('etc/dsn.'._get('env').'.php');
//end metrodb

@include('etc/email.'._get('env').'.php');

//metrou
_connect('authenticate', 'metrou/authenticator.php');

_didef('authorizer', 'metrou/authorizer.php',
    array('metrou', '/login', '/dologin', '/logout', '/dologout', '/register', '/token', '/oauth', '/test')
);
$authorizer = _make('authorizer');
_connect('authorize',            $authorizer);

//events
_connect('access.denied',        'metrou/login.php::accessDenied');
_connect('authenticate.success', 'metrou/login.php::authSuccess');
_connect('authenticate.failure', 'metrou/login.php::authFailure');


//things
_didef('user',           'metrou/user.php');
_didef('session',        'metrou/sessiondb.php');
//end metrou


_set('template_basedir', 'templates/');
_set('template_baseuri', 'templates/');
_set('template_name',    'authbox');
_set('site_title',       'Authbox');
_set('noreply_email',    'noreply@example.com');

_set('auto_route_to_main', FALSE);
_set('route_rules',  array() );

_set('route_rules',
	array_merge(array('/:appName'=>array( 'modName'=>'main', 'actName'=>'main' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/:appName/:modName'=>array( 'actName'=>'main' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/:appName/:modName/:actName'=>array(  )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/:appName/:modName/:actName/:arg'=>array(  )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/register/:actName/:arg'=>array( 'appName'=>'metrou', 'modName'=>'register' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/register/:actName/:arg'=>array( 'appName'=>'metrou', 'modName'=>'register' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/register/verify/:arg'=>array( 'appName'=>'metrou', 'modName'=>'verify' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/register/password/:actName/:arg'=>array( 'appName'=>'metrou', 'modName'=>'password' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/login'=>array( 'appName'=>'metrou', 'modName'=>'login', 'actName'=>'main' )),
	_get('route_rules')));

#oauth2
_set('route_rules',
	array_merge(array('/userinfo'=>array( 'appName'=>'oauth', 'modName'=>'userinfo', 'actName'=>'main' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/.well-known/openid-configuration'=>array( 'appName'=>'oauth', 'modName'=>'openid', 'actName'=>'config' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/authorize'=>array( 'appName'=>'oauth', 'modName'=>'authorize', 'actName'=>'main' )),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/authorize/main/:actName'=>array( 'appName'=>'oauth', 'modName'=>'authorize')),
	_get('route_rules')));

_set('route_rules',
	array_merge(array('/token'=>array( 'appName'=>'oauth', 'modName'=>'token', 'actName'=>'main' )),
	_get('route_rules')));



if (_get('env') == 'dev' || _get('env') == 'demo') {
	_connect('template.extraJs', function ($request) {

		if (!$request->isAjax) {
	echo '<script type=\'text/javascript\' id="__bs_script__">//<![CDATA[
		document.write("<script async src=\'//HOST:35729/livereload.js\'><\/script>".replace("HOST", "localhost"));
	//]]></script>
		';
		}
	}, 3);

	_connect('hangup', function() {
	register_shutdown_function( function() {
		global $metrofw_start;
		$log = \_make('logService');
		$log->debug("Execution time: ".(sprintf("%0.4f", (microtime(1) - $metrofw_start)*1000))." ms.");
	});
	}, 3);

}
