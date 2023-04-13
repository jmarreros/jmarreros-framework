<?php

namespace Jmarreros\Test\Routing\Routing;

use Closure;
use Jmarreros\Http\HttpMethod;
use Jmarreros\Http\Request;
use Jmarreros\Http\Response;
use Jmarreros\Routing\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {
    private function createMockRequest(string $uri, HttpMethod $method, array $headers =[]): Request {
        return (new Request())
            ->setUri($uri)
            ->setMethod($method)
            ->setHeaders($headers);
    }

    public function test_resolve_basic_route_with_callback_action() {
        $uri    = '/test';
        $action = fn () => "test";

        $router = new Router();
        $router->get($uri, $action);

        //		$route = $router->resolve( new Request(new MockServer($uri, HttpMethod::GET)) );
        $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::GET));
        $this->assertEquals($action, $route->action());
        $this->assertEquals($uri, $route->uri());
    }

    public function test_resolve_multiple_basic_routes_with_callback_action() {
        $routes = [
            '/test'              => fn () => 'test',
            '/foo'               => fn () => 'foo',
            '/bar'               => fn () => 'bar',
            '/long/nested/route' => fn () => 'long nested',
        ];

        $router = new Router();

        foreach ($routes as $uri => $action) {
            $router->get($uri, $action);
        }

        foreach ($routes as $uri => $action) {
            $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::GET));
            $this->assertEquals($action, $route->action());
        }
    }

    public function test_resolve_multiple_basic_routes_with_callback_action_for_different_http_methods() {
        $routes = [
            [ HttpMethod::GET, "/test", fn () => "get" ],
            [ HttpMethod::POST, "/test", fn () => "post" ],
            [ HttpMethod::PUT, "/test", fn () => "put" ],
            [ HttpMethod::PATCH, "/test", fn () => "patch" ],
            [ HttpMethod::DELETE, "/test", fn () => "delete" ],

            [ HttpMethod::GET, "/random/get", fn () => "get" ],
            [ HttpMethod::POST, "/random/nested/put", fn () => "post" ],
            [ HttpMethod::PUT, "/put/random/route", fn () => "put" ],
            [ HttpMethod::PATCH, "/some/patch/route", fn () => "patch" ],
            [ HttpMethod::DELETE, "/d", fn () => "delete" ],
        ];

        $router = new Router();

        foreach ($routes as [$method, $uri, $action]) {
            $router->{strtolower($method->value)}($uri, $action);
        }

        foreach ($routes as [$method, $uri, $action]) {
            $route = $router->resolveRoute($this->createMockRequest($uri, $method));
            $this->assertEquals($action, $route->action());
            $this->assertEquals($uri, $route->uri());
        }
    }


    public function test_run_middlewares() {
        $middleware1 = new class () {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                $response->setHeader('x-test-one', 'test one');
                return $response;
            }
        };

        $middleware2 = new class () {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                $response->setHeader('x-test-two', 'test two');
                return $response;
            }
        };

        $router = new Router();
        $expectedResponse = Response::text("test");
        $uri = '/test';

        $router->get($uri, fn ($request) => $expectedResponse)
            ->setMiddleware([$middleware1::class, $middleware2::class]);

        $response = $router->resolve($this->createMockRequest($uri, HttpMethod::GET));

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals('test one', $response->headers('x-test-one'));
        $this->assertEquals('test two', $response->headers('x-test-two'));
    }

    public function test_middleware_stack_can_be_stopped() {
        $middleware1 = new class () {
            public function handle(Request $request, Closure $next): Response {
                return $next($request);
            }
        };

        $middleware2 = new class () {
            public function handle(Request $request, Closure $next): Response {
                if ($request->headers('x-test') === 'abort') {
                    return Response::text('respuesta desde middleware');
                }

                return  $next($request);
            }
        };

        $router = new Router();
        $expectedResponse =  Response::text('respuesta desde middleware');
        $myResponse = Response::text("devuelve response");
        $uri = '/test';

        $router->get($uri, fn ($request) => $myResponse)
               ->setMiddleware([$middleware1, $middleware2]);

        $response = $router->resolve($this->createMockRequest($uri, HttpMethod::GET, ['x-test' =>'abort']));
        $this->assertEquals($expectedResponse, $response);
    }
}



//			match($method){
//				HttpMethod::GET => $router->get($uri, $action),
//				HttpMethod::POST => $router->post($uri, $action),
//				HttpMethod::PUT => $router->put($uri, $action),
//				HttpMethod::PATCH => $router->patch($uri, $action),
//				HttpMethod::DELETE => $router->delete($uri, $action),
//			};
