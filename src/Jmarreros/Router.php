<?php
namespace Jmarreros;

use Closure;

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
		foreach ($this->routes[$method] as $route){
			if ($route->matches($uri)){
				return $route;
			}
		}
		throw new HttpNotFoundException();
	}

	protected function registerRoute(HttpMethods $method, string $uri, Closure $action){
		$this->routes[$method->value][] = new Route($uri, $action);
	}

	public function get(string $uri, Closure $action){
		$this->registerRoute(HttpMethods::GET, $uri, $action);
	}

	public function post(string $uri, callable $action){
		$this->registerRoute(HttpMethods::POST, $uri, $action);
	}

	public function put(string $uri, callable $action){
		$this->registerRoute(HttpMethods::PUT, $uri, $action);
	}

	public function patch(string $uri, callable $action){
		$this->registerRoute(HttpMethods::PATCH, $uri, $action);
	}

	public function delete(string $uri, callable $action){
		$this->registerRoute(HttpMethods::DELETE, $uri, $action);
	}
}