<?php

namespace Jmarreros;

class Server {
	public function requestUri():string{
		return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
	}

	public function requestMethod():HttpMethods{
		return HttpMethods::from($_SERVER["REQUEST_METHOD"]);
	}

	public function postData():array{
		return $_POST;
	}

	public function queryParams():array{
		return $_GET;
	}
}