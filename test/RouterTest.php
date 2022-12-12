<?php

namespace Jmarreros\Test;

use Jmarreros\HttpMethods;
use Jmarreros\Request;
use Jmarreros\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {
	public function test_resolve_basic_route_with_callback_action() {
		$uri    = '/test';
		$action = fn() => "test";

		$router = new Router();
		$router->get( $uri, $action );

		$route = $router->resolve( new Request(new MockServer($uri, HttpMethods::GET)) );
		$this->assertEquals( $action, $route->action() );
		$this->assertEquals( $uri, $route->uri() );
	}

	public function test_resolve_multiple_basic_routes_with_callback_action() {
		$routes = [
			'/test'              => fn() => 'test',
			'/foo'               => fn() => 'foo',
			'/bar'               => fn() => 'bar',
			'/long/nested/route' => fn() => 'long nested',
		];

		$router = new Router();

		foreach ( $routes as $uri => $action ) {
			$router->get( $uri, $action );
		}

		foreach ( $routes as $uri => $action ) {
			$route = $router->resolve( new Request(new MockServer($uri, HttpMethods::GET))  );
			$this->assertEquals( $action, $route->action() );
		}
	}

	public function test_resolve_multiple_basic_routes_with_callback_action_for_different_http_methods() {
		$routes = [
			[ HttpMethods::GET, "/test", fn() => "get" ],
			[ HttpMethods::POST, "/test", fn() => "post" ],
			[ HttpMethods::PUT, "/test", fn() => "put" ],
			[ HttpMethods::PATCH, "/test", fn() => "patch" ],
			[ HttpMethods::DELETE, "/test", fn() => "delete" ],

			[ HttpMethods::GET, "/random/get", fn() => "get" ],
			[ HttpMethods::POST, "/random/nested/put", fn() => "post" ],
			[ HttpMethods::PUT, "/put/random/route", fn() => "put" ],
			[ HttpMethods::PATCH, "/some/patch/route", fn() => "patch" ],
			[ HttpMethods::DELETE, "/d", fn() => "delete" ],
		];

		$router = new Router();

		foreach ( $routes as [$method, $uri, $action] ) {
			$router->{strtolower( $method->value )}( $uri, $action );
		}

		foreach ( $routes as [$method, $uri, $action] ) {
			$route = $router->resolve( new Request(new MockServer($uri, $method))  );
			$this->assertEquals( $action, $route->action() );
			$this->assertEquals( $uri, $route->uri() );
		}


	}
}



//			match($method){
//				HttpMethods::GET => $router->get($uri, $action),
//				HttpMethods::POST => $router->post($uri, $action),
//				HttpMethods::PUT => $router->put($uri, $action),
//				HttpMethods::PATCH => $router->patch($uri, $action),
//				HttpMethods::DELETE => $router->delete($uri, $action),
//			};