<?php
require_once "../vendor/autoload.php";

use Jmarreros\Http\HttpNotFoundException;
use Jmarreros\Http\Request;

use Jmarreros\Http\Response;
use Jmarreros\Routing\Router;
use Jmarreros\Server\PHPNativeServer;

$router = new Router();

$router->get( '/test', function ( Request $request ) {
	$response = new Response();
	$response->setHeader( "Content-Type", "Application/json" );
	$response->setContent( json_encode( [ "message" => "Get OK 🚀" ] ) );

	return $response;
} );

$router->post( '/test', function ( Request $request ) {
	return "Get Post OK 🤚";
} );

$router->put( '/test', function ( Request $request ) {
	return "PUT OK 🤚";
} );

$router->patch( '/test', function ( Request $request ) {
	return "PATCH OK 🤚";
} );

$router->delete( '/test', function ( Request $request ) {
	return "DELETE OK 🤚";
} );

$server   = new PHPNativeServer();

try {
	$request  = new Request( $server );
	$route    = $router->resolve( $request );
	$action   = $route->action();
	$response = $action( $request );
	$server->sendResponse($response);
//	print( $action() );

} catch ( HttpNotFoundException $e ) {
//	print( "Not Found 🤔" );
//	http_response_code( 404 );
	$response = new Response();
	$response->setStatus(404);
	$response->setContent("Not Found");
	$response->setHeader("Content-Type", "text/plain");
	$server->sendResponse($response);
}

//var_dump($router);
//	$method = $_SERVER["REQUEST_METHOD"];
//	$uri    = $_SERVER["REQUEST_URI"];
//	$route = new Route( '/test/{test}/user/{user}', fn() => "test" );
//	var_dump($route->parseParameters('/test/1/user/3'));