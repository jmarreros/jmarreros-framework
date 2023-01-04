<?php

namespace Jmarreros\Http;

use Jmarreros\Routing\Route;

/**
 * HTTP request.
 */
class Request {
	/**
	 * URI requested by the client.
	 *
	 * @var string
	 */
	protected string $uri;
	/**
	 * Route match by uri
	 *
	 * @var Route
	 */
	protected Route $route;
	/**
	 * HTTP method used for this request.
	 *
	 * @var HttpMethod
	 */
	protected HttpMethod $method;

	/**
	 * POST data.
	 *
	 * @var array
	 */
	protected array $data;

	/**
	 * Query parameters.
	 *
	 * @var array
	 */
	protected array $query;

	/**
	 * Headers data
	 *
	 * @var array
	 */
	protected array $headers = [];


	/**
	 * Get the request URI.
	 *
	 * @return string
	 */
	public function uri(): string {
		return $this->uri;
	}

	/**
	 * Set request uri
	 *
	 * @param string $uri
	 *
	 * @return self
	 */
	public function setUri( string $uri ): self {
		$this->uri = $uri;

		return $this;
	}

	/**
	 * Get route match by the URI of this request
	 *
	 * @return Route
	 */
	public function route(): Route {
		return $this->route;
	}


	/**
	 * Set route for this request
	 *
	 * @param Route $route
	 *
	 * @return self
	 */
	public function setRoute( Route $route ): self {
		$this->route = $route;

		return $this;
	}

	/**
	 * Get the request HTTP method.
	 *
	 * @return HttpMethod
	 */
	public function method(): HttpMethod {
		return $this->method;
	}

	/**
	 * Set HttpMethod
	 *
	 * @param HttpMethod $method
	 *
	 * @return self
	 */
	public function setMethod( HttpMethod $method ): self {
		$this->method = $method;

		return $this;
	}

	/**
	 * Get headers or specific header
	 *
	 * @param string|null $key
	 *
	 * @return array|string|null
	 */
	public function headers( string $key = null ): array|string|null {
		if ( is_null( $key ) ) {
			return $this->data;
		}

		return $this->data[ strtolower( $key ) ] ?? null;
	}

	/**
	 * Set headers as array
	 *
	 * @param array $headers
	 *
	 * @return $this
	 */
	public function setHeaders( array $headers ): self {
		foreach ( $headers as $header => $value ) {
			$this->headers[ strtolower( $header ) ] = $value;
		}

		return $this;
	}


	/**
	 * Get post data
	 *
	 * @param string|null $key
	 *
	 * @return array
	 */
	public function data( ?string $key = null ): array {
		$result = $this->data;
		if ( $key ) {
			return isset( $result[ $key ] ) ? [ $key => $result[ $key ] ] : [ $key => null ];
		}

		return $result;
	}

	/**
	 * Set post data
	 *
	 * @param array $data
	 *
	 * @return self
	 */
	public function setPostData( array $data ): self {
		$this->data = $data;

		return $this;
	}

	/**
	 *  Get all query parameters
	 *
	 * @param string|null $key
	 *
	 * @return array
	 */
	public function query( ?string $key = null ): array {
		$result = $this->query;
		if ( $key ) {
			return isset( $result[ $key ] ) ? [ $key => $result[ $key ] ] : [ $key => null ];
		}

		return $result;
	}

	/**
	 * Set query parameters
	 *
	 * @param array $query
	 *
	 * @return self
	 */
	public function setQueryParametes( array $query ): self {
		$this->query = $query;

		return $this;
	}

	/**
	 * Get all route parameters
	 *
	 * @param string|null $key
	 *
	 * @return array
	 */
	public function routeParameters( ?string $key = null ): array {
		$params = $this->route->parseParameters( $this->uri );
		if ( $key ) {
			return isset( $params[ $key ] ) ? [ $key => $params[ $key ] ] : [ $key => null ];
		}

		return $params;
	}
}
