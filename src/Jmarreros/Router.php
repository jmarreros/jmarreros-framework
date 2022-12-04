<?php
namespace Jmarreros;

class Router{
	protected array $routes = [];

	public function __construct(){
		foreach (HttpMethods::cases() as $method){
			$this->routes[$method->value] = [];
		}
	}

	/**
	 * @throws HttpNotFoundException
	 */
	public function resolve(string $uri, string $method){
		$action = $this->routes[$method][$uri] ?? null;
		if ( is_null($action) ) throw new HttpNotFoundException();

		return $action;
	}

	public function get(string $uri, callable $action){
		$this->routes[HttpMethods::GET->value][$uri] = $action;
	}

	public function post(string $uri, callable $action){
		$this->routes[HttpMethods::POST->value][$uri] = $action;
	}

	public function put(string $uri, callable $action){
		$this->routes[HttpMethods::PUT->value][$uri] = $action;
	}

	public function patch(string $uri, callable $action){
		$this->routes[HttpMethods::PATCH->value][$uri] = $action;
	}

	public function delete(string $uri, callable $action){
		$this->routes[HttpMethods::DELETE->value][$uri] = $action;
	}
}