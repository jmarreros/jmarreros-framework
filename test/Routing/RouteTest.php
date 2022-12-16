<?php

namespace Jmarreros\Test\Routing;

use PHPUnit\Framework\TestCase;
use Jmarreros\Routing\Route;

class RouteTest extends TestCase {
	public function routes_with_not_parameters() {
		return [
			[ '/' ],
			[ '/test' ],
			[ '/test/nested' ],
			[ '/test/another/nested' ],
			[ '/test/another/nested/route' ],
		];
	}

	/**
	 * @dataProvider routes_with_not_parameters
	 */
	public function test_regex_with_not_parameters( string $uri ) {
		$route = new Route( $uri, fn() => 'test' );
		$this->assertTrue( $route->matches( $uri ) );
		$this->assertFalse( $route->matches( "$uri/extra/path" ) );
		$this->assertFalse( $route->matches( "/some/path/$uri" ) );
		$this->assertFalse( $route->matches( "/random/route" ) );
	}

	/**
	 * @dataProvider routes_with_not_parameters
	 */
	public function test_regex_on_uri_ends_with_slash(string $uri){
		$route = new Route( $uri, fn() => 'test' );
		$this->assertTrue( $route->matches( "$uri/" ) );
	}

	public function routes_with_parameters() {
		return [
			[ '/test/{test}', '/test/1' , [ 'test' => 1] ],
			[ '/users/{user}', '/users/2', [ 'user' => 2 ]],
			[ '/test/{test}', '/test/string', ['test' => 'string'] ],
			['/test/nested/{route}', '/test/nested/5', ['route' => 5 ]],
			['/test/{param}/long/{test}/with/{multiple}/params', '/test/12345/long/5/with/yellow/params',
				['param' => 12345, 'test' => 5, 'multiple' => 'yellow']],
		];
	}

	/**
	 * @dataProvider routes_with_parameters
	 */
	public function test_regex_with_parameters( string $definition, string $uri ) {
		$route = new Route( $definition, fn() => 'test' );
		$this->assertTrue( $route->matches( $uri ) );
		$this->assertFalse( $route->matches( "$uri/extra/path" ) );
		$this->assertFalse( $route->matches( "/some/path/$uri" ) );
		$this->assertFalse( $route->matches( "/random/route" ) );
	}

	/**
	 * @dataProvider routes_with_parameters
	 */
	public function test_parse_parameters(string $definition, string $uri, array $expectedParameters){
		$route = new Route( $definition, fn() => 'test' );
		$this->assertTrue($route->hasParameters());
		$this->assertEquals($expectedParameters, $route->parseParameters($uri));
	}
}