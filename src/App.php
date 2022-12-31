<?php

namespace Jmarreros;

use Jmarreros\Container\Container;
use Jmarreros\Http\HttpNotFoundException;
use Jmarreros\Http\Request;
use Jmarreros\Http\Response;
use Jmarreros\Routing\Router;
use Jmarreros\Server\PhpNativeServer;
use Jmarreros\Server\Server;

class App {
	public Router $router;
	public Request $request;
	public Server $server;

	public static function bootstrap() {
		$app = Container::singleton(self::class);
		$app->router  = new Router;
		$app->server  = new PhpNativeServer();
		$app->request = $app->server->getRequest();

		return $app;
	}

	public function run(){
		try {
			$route   = $this->router->resolve( $this->request );
			$this->request->setRoute( $route );
			$action   = $route->action();
			$response = $action( $this->request );
			$this->server->sendResponse( $response );
		} catch ( HttpNotFoundException $e ) {
			$response = Response::text( "Not Found" )->setStatus( 404 );
			$this->server->sendResponse( $response );
		}
	}

}