<?php

namespace Jmarreros;

use Jmarreros\Http\HttpMethods;

Interface Server {
	public function requestUri():string;
	public function requestMethod():HttpMethods;
	public function postData():array;
	public function queryParams():array;
}