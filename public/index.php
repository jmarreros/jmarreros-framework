<?php
require_once "../vendor/autoload.php";

use Jmarreros\HttpNotFoundException;
use Jmarreros\Request;
use Jmarreros\Router;
use Jmarreros\Route;
use Jmarreros\Server;

$router = new Router();

$router->get( '/test', function () {
	return "Get OK 🚀";
} );

$router->post( '/test', function () {
	return "Get Post OK 🤚";
} );

$router->put( '/test', function () {
	return "PUT OK 🤚";
} );

$router->patch( '/test', function () {
	return "PATCH OK 🤚";
} );

$router->delete( '/test', function () {
	return "DELETE OK 🤚";
} );

try {
//	$method = $_SERVER["REQUEST_METHOD"];
//	$uri    = $_SERVER["REQUEST_URI"];
	$route = $router->resolve( new Request( new Server() ) );
	$action = $route->action();
	print( $action() );
//	$route = new Route( '/test/{test}/user/{user}', fn() => "test" );
//	var_dump($route->parseParameters('/test/1/user/3'));
} catch ( HttpNotFoundException $e ) {
	print( "Not Found 🤔" );
	http_response_code( 404 );
}

//var_dump($router);