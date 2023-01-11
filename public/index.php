<?php
require_once "../vendor/autoload.php";

use Jmarreros\App;
use Jmarreros\Http\Middleware;
use Jmarreros\Http\Request;
use Jmarreros\Http\Response;
use Jmarreros\Routing\Route;

//$router = new Router();
$app = App::bootstrap();

$app->router->get( '/test', function ( Request $request ) {
	return Response::json( $request->query() );
} );

$app->router->get( '/test/{param}', function ( Request $request ) {
	return Response::json( $request->routeParameters() );
//	return Response::json($request->query());
//	return Response::json( [ "message" => "Get ok 🙂" ] );
//	return Response::text("Get Ok");
} );

$app->router->post( '/test', function ( Request $request ) {
//	return Response::text("Post Ok");
	return Response::json( $request->data() );
} );

$app->router->get( '/redirect', function ( Request $request ) {
	return Response::redirect( "/test" );
} );


$app->router->put( '/test', function ( Request $request ) {
	return Response::json( $request->data() );
} );

$app->router->patch( '/test', function ( Request $request ) {
	return "PATCH OK 🤚";
} );

$app->router->delete( '/test', function ( Request $request ) {
	return "DELETE OK 🤚";
} );

class AuthMiddleware implements Middleware {
	public function handle( Request $request, \Closure $next ): Response {
		if ( $request->headers( 'Authorization' ) != 'test' ) {
			return Response::json( [ "message" => "Not authenticated" ] )->setStatus( 401 );
		}

		$response = $next( $request );
		$response->setHeader( 'X-Test-Custom-Header', 'Hola 🙂' );

		return $response;
	}
}

Route::get( '/middlewares', fn( Request $request ) => Response::json( [ "message" => "OK" ] ) )
     ->setMiddleware( [ AuthMiddleware::class ] );

// Views
Route::get('/html', fn(Request $request) => Response::view('home', [
	'user' => 'Manolo'
]));

$app->run();

//	print( "Not Found 🤔" );
//	http_response_code( 404 );
//	$response = new Response();
//	$response->setStatus( 404 );
//	$response->setContent( "Not Found" );
//	$response->setHeader( "Content-Type", "text/plain" );
//	$server->sendResponse( $response );

//var_dump($app->router);
//	$method = $_SERVER["REQUEST_METHOD"];
//	$uri    = $_SERVER["REQUEST_URI"];
//	$route = new Route( '/test/{test}/user/{user}', fn() => "test" );
//	var_dump($route->parseParameters('/test/1/user/3'));