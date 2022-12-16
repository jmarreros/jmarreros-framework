<?php

namespace Jmarreros\Server;

use Jmarreros\Http\HttpMethods;
use Jmarreros\Http\Response;

class PHPNativeServer implements Server {
	public function requestUri(): string {
		return parse_url( $_SERVER["REQUEST_URI"], PHP_URL_PATH );
	}

	public function requestMethod(): HttpMethods {
		return HttpMethods::from( $_SERVER["REQUEST_METHOD"] );
	}

	public function postData(): array {
		return $_POST;
	}

	public function queryParams(): array {
		return $_GET;
	}


	public function sendResponse( Response $response ): void {
		// PHP send content-type by default
		header("Content-Type: None");
		header_remove("Content-Type");

		$response->prepare();
		http_response_code( $response->status() );
		foreach ( $response->headers() as $header => $value ) {
			header("$header: $value");
		}

		print($response->content());
	}
}