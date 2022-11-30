<?php
require "Router.php";

$router = new Router();

$router->get('/test', function(){
	return "Get OK 🚀";
});

$router->post('/test', function(){
	return "Get Post OK 🤚";
});

try {
	$action = $router->resolve();
	print( $action() );
} catch (HttpNotFoundException $e){
	print("Not Found 🤔");
	http_response_code(404);
}

//var_dump($router);