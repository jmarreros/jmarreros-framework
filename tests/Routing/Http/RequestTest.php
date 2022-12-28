<?php

namespace Jmarreros\Test\Routing\Http;

use Jmarreros\Http\HttpMethod;
use Jmarreros\Http\Request;
use Jmarreros\Server\Server;

use PhpParser\Builder\Method;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {
    private function createMockRequest(string $uri, HttpMethod $method, array $query, array $data): Request {
//        $mock = $this->getMockBuilder(Server::class)->getMock();
//        $mock->method('requestUri')->willReturn($uri);
//        $mock->method('requestMethod')->willReturn($method);
//        $mock->method('queryParams')->willReturn($query);
//        $mock->method('postData')->willReturn($data);

        return (new Request)
	            ->setUri($uri)
	            ->setMethod($method)
	            ->setQueryParametes($query)
	            ->setPostData($data);
    }

    public function test_request_returns_data_obtained_from_server_correctly() {
        $uri   = '/test';
        $query = [ 's' => 'woocommerce' ];
        $data  = [ 'name' => 'Jhon', 'Apellido' => 'Marreros' ];

        $request = $this->createMockRequest($uri, HttpMethod::POST, $query, $data);

        $this->assertEquals($uri, $request->uri());
        $this->assertEquals($query, $request->query());
        $this->assertEquals($data, $request->data());
        $this->assertEquals(HttpMethod::POST, $request->method());
    }
}
