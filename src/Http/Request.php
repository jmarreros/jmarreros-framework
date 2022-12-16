<?php

namespace Jmarreros\Http;

use Jmarreros\Server\Server;

class Request {
	protected string $uri;
	protected HttpMethods $method;
	protected array $data;
	protected array $query;

	public function __construct(Server $server) {
		$this->uri = $server->requestUri();
		$this->method = $server->requestMethod();
		$this->data = $server->postData();
		$this->query = $server->queryParams();
	}

	public function uri():string{
		return $this->uri;
	}

	public function method():HttpMethods{
		return $this->method;
	}
}