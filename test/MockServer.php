<?php

namespace Jmarreros\Test;

use Jmarreros\HttpMethods;
use Jmarreros\Server;

class MockServer implements Server {
	public function __construct(public string $uri, public HttpMethods $method) {
		$this->uri = $uri;
		$this->method = $method;
	}

	public function requestUri():string{
		return $this->uri;
	}

	public function requestMethod():HttpMethods{
		return $this->method;
	}

	public function postData():array{
		return [];
	}

	public function queryParams():array{
		return [];
	}

}