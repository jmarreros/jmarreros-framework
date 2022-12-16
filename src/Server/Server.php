<?php

namespace Jmarreros\Server;

use Jmarreros\Http\HttpMethods;
use Jmarreros\Http\Response;

Interface Server {
	public function requestUri():string;
	public function requestMethod():HttpMethods;
	public function postData():array;
	public function queryParams():array;
	public function sendResponse(Response $response):void;
}