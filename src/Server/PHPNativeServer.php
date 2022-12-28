<?php

namespace Jmarreros\Server;

use Jmarreros\Http\HttpMethod;
use Jmarreros\Http\Request;
use Jmarreros\Http\Response;

/**
 * PHP native server that uses `$_SERVER` global.
 */
class PhpNativeServer implements Server {

    /**
     * @inheritDoc
     */
    public function getRequest(): Request {
		return (new Request())
			->setUri(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH))
			->setMethod(HttpMethod::from($_SERVER["REQUEST_METHOD"]))
			->setPostData($_POST)
			->setQueryParametes($_GET);
    }

    /**
     * @inheritDoc
     */
    public function sendResponse(Response $response) {
        // PHP sends Content-Type header by default, but it has to be removed if
        // the response has not content. Content-Type header can't be removed
        // unless it is set to some value before.
        header("Content-Type: None");
        header_remove("Content-Type");

        $response->prepare();
        http_response_code($response->status());
        foreach ($response->headers() as $header => $value) {
            header("$header: $value");
        }
        print($response->content());
    }
}
