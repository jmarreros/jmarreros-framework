<?php

namespace Jmarreros;

use Jmarreros\Http\HttpNotFoundException;
use Jmarreros\Http\Request;
use Jmarreros\Http\Response;
use Jmarreros\Routing\Router;
use Jmarreros\Server\PhpNativeServer;
use Jmarreros\Server\Server;
use Jmarreros\View\JmarrerosEngine;
use Jmarreros\View\View;

class App {
	public Router $router;
	public Request $request;
	public Server $server;
	public View $view;

	public static function bootstrap() {
		$app = singleton(self::class);
		$app->router  = new Router;
		$app->server  = new PhpNativeServer();
		$app->request = $app->server->getRequest();
		$app->view = new JmarrerosEngine(__DIR__."/../views");

		return $app;
	}

	public function run(){
		try {
			$response   = $this->router->resolve( $this->request );
			$this->server->sendResponse( $response );
		} catch ( HttpNotFoundException $e ) {
			$response = Response::text( "Not Found" )->setStatus( 404 );
			$this->server->sendResponse( $response );
		}
	}

}