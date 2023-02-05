<?php

namespace Jmarreros\Session;

use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Builder\Interface_;

interface SessionStorage {

	public function start();

	public function id(): string;

	public function get( string $key, $default = null );

	public function set( string $key, mixed $value );

	public function has( string $key ): Boolean;

	public function remove( string $key );

	public function destroy();
}