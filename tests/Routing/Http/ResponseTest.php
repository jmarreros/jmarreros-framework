<?php

namespace Jmarreros\Test\Routing\Http;


use Jmarreros\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase {
	public function test_json_response_is_constructed_correctly() {
		$data = ['key' => 'value'];
		$response = Response::json($data);

		$this->assertEquals( 200, $response->status() );
		$this->assertEquals('application/json', $response->headers()['content-type'] );
		$this->assertEquals(json_encode($data), $response->content());
	}

	public function test_text_response_is_constructed_correctly() {
		$data = "Test";
		$response = Response::text($data);

		$this->assertEquals( 200, $response->status() );
		$this->assertEquals('text/plain', $response->headers()['content-type'] );
		$this->assertEquals($data, $response->content());
	}

	public function test_redirect_response_is_constructed_correctly() {
		$uri = '/test';
		$response = Response::redirect($uri);

		$this->assertEquals( 302, $response->status() );
		$this->assertEquals($uri, $response->headers()['location']);
	}

	public function test_prepare_method_removes_content_headers_if_there_is_no_content() {
		$response = Response::redirect('\test');
		$response->prepare();

		$this->assertArrayNotHasKey('content-type', $response->headers());
		$this->assertArrayNotHasKey('Content-Length', $response->headers());
	}

	public function test_prepare_method_adds_content_length_header_if_there_is_content() {
		$data = "Test";
		$response = Response::text($data);

		$response->prepare();
		$this->assertEquals(4, $response->headers()['content-length']);
	}
}
