<?php

namespace Jmarreros\Session;

use http\Exception\RuntimeException;
use phpDocumentor\Reflection\Types\Boolean;

class PhpNativeSessionStorage implements SessionStorage {

	public function start() {
		if ( ! session_start() ) {
			throw new RuntimeException( 'Fail starting session' );
		}
	}

	public function id(): string {
		return session_id();
	}

	public function get( string $key, $default = null ) {
		return $_SESSION[ $key ] ?? $default;
	}

	public function set( string $key, mixed $value ) {
		$_SESSION[ $key ] = $value;
	}

	public function has( string $key ): Boolean {
		return isset( $_SESSION[ $key ] );
	}

	public function remove( string $key ) {
		unset( $_SESSION[ $key ] );
	}

	public function destroy() {
		session_destroy();
	}
}