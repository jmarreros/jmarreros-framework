<?php

namespace Jmarreros;

use Jmarreros\Http\HttpNotFoundException;
use Jmarreros\Http\Request;
use Jmarreros\Http\Response;
use Jmarreros\Routing\Router;
use Jmarreros\Server\PhpNativeServer;
use Jmarreros\Server\Server;
use Jmarreros\Validation\Exceptions\ValidationException;
use Jmarreros\View\JmarrerosEngine;
use Jmarreros\View\View;

class App {
	public Router $router;
	public Request $request;
	public Server $server;
	public View $view;

	public static function bootstrap() {
		$app          = singleton( self::class );
		$app->router  = new Router;
		$app->server  = new PhpNativeServer();
		$app->request = $app->server->getRequest();
		$app->view    = new JmarrerosEngine( __DIR__ . "/../views" );

		return $app;
	}

	public function run() {
		try {
			$response = $this->router->resolve( $this->request );
			$this->server->sendResponse( $response );
		} catch ( HttpNotFoundException $e ) {
			$this->abort( Response::text( "Not Found" )->setStatus( 404 ) );
		} catch ( ValidationException $e ) {
			$this->abort( json( $e->errors() )->setStatus( 422 ) );
		} catch ( \Throwable $e ) {
			$response = json( [
				'message' => $e->getMessage(),
				'trace'   => $e->getTrace()
			] );
			$this->abort( $response );
		}
	}

	public function abort( Response $response ) {
		$this->server->sendResponse( $response );
	}
}