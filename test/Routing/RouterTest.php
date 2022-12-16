<?php

namespace Jmarreros\Test\Routing;

use Jmarreros\Http\HttpMethods;
use Jmarreros\Http\Request;
use PHPUnit\Framework\TestCase;
use Jmarreros\Routing\Router;
use Jmarreros\Server\Server;

class RouterTest extends TestCase {

	private function createMockRequest(string $uri, HttpMethods $method): Request{
		$mock = $this->getMockBuilder(Server::class)->getMock();
		$mock->method('requestUri')->willReturn($uri);
		$mock->method('requestMethod')->willReturn($method);

		return new Request($mock);
	}

	public function test_resolve_basic_route_with_callback_action() {
		$uri    = '/test';
		$action = fn() => "test";

		$router = new Router();
		$router->get( $uri, $action );

//		$route = $router->resolve( new Request(new MockServer($uri, HttpMethods::GET)) );
		$route = $router->resolve( $this->createMockRequest($uri, HttpMethods::GET) );
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
			$route = $router->resolve( $this->createMockRequest($uri, HttpMethods::GET)  );
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
			$route = $router->resolve( $this->createMockRequest($uri, $method)  );
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