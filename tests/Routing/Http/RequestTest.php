<?php

namespace Jmarreros\Test\Routing\Http;

use Jmarreros\Http\HttpMethod;
use Jmarreros\Http\Request;

use Jmarreros\Routing\Router;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {
	private function createMockRequest( string $uri, HttpMethod $method, array $query, array $data ): Request {
//        $mock = $this->getMockBuilder(Server::class)->getMock();
//        $mock->method('requestUri')->willReturn($uri);
//        $mock->method('requestMethod')->willReturn($method);
//        $mock->method('queryParams')->willReturn($query);
//        $mock->method('postData')->willReturn($data);

		return ( new Request )
			->setUri( $uri )
			->setMethod( $method )
			->setQueryParametes( $query )
			->setPostData( $data );
	}

	public function test_request_returns_data_obtained_from_server_correctly() {
		$uri   = '/test';
		$query = [ 's' => 'woocommerce' ];
		$data  = [ 'name' => 'Jhon', 'Apellido' => 'Marreros' ];

		$request = $this->createMockRequest( $uri, HttpMethod::POST, $query, $data );

		$this->assertEquals( $uri, $request->uri() );
		$this->assertEquals( $query, $request->query() );
		$this->assertEquals( $data, $request->data() );
		$this->assertEquals( HttpMethod::POST, $request->method() );
	}

	public function test_data_returns_value_if_key_is_given() {
		$uri     = '/test';
		$data    = [ 'name' => 'Jhon', 'Apellido' => 'Marreros' ];
		$query = [ 's' => 'woocommerce' ];
		$request = $this->createMockRequest( $uri, HttpMethod::POST, $query, $data );

		$this->assertEquals( [ 'name' => 'Jhon' ], $request->data( 'name' ) );
		$this->assertEquals( [ 'namex' => null ], $request->data( 'namex' ) );
		$this->assertEquals( $data, $request->data() );
	}


	public function test_query_returns_value_if_key_is_given() {
		$uri     = '/test';
		$data    = [];
		$query   = [ 's' => 'woocommerce', 'param' => 'test' ];
		$request = $this->createMockRequest( $uri, HttpMethod::GET, $query, $data );

		$this->assertEquals( [ 's' => 'woocommerce' ], $request->query( 's' ) );
		$this->assertEquals( [ 'sxx' => null ], $request->query( 'sxx' ) );
		$this->assertEquals( $query, $request->query() );
	}

	public function test_route_parameters_returns_value_if_key_is_given() {
		$router = new Router();

		$router->get( '/test/{param}/{x}', function ( Request $request ) {
			return $request->routeParameters();
		} );

		$uri     = '/test/paramres/xres';
		$request = $this->createMockRequest( $uri, HttpMethod::GET, [], [] );
		$route   = $router->resolveRoute( $request );
		$request->setRoute( $route );

		$this->assertEquals( [ 'param' => 'paramres' ], $request->routeParameters( 'param' ) );
		$this->assertEquals( [ 'paramx' => null ], $request->routeParameters( 'paramx' ) );
		$this->assertEquals( [ 'param' => 'paramres', 'x' => 'xres' ], $request->routeParameters() );
	}
}
