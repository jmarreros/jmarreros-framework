<?php

namespace Jmarreros\Validation\Exceptions;

use Jmarreros\Exceptions\JmarrerosException;

class ValidationException extends JmarrerosException {

	public function __construct( protected array $errors ) {

	}

	public function errors(): array {
		return $this->errors;
	}
}