<?php

namespace Jmarreros\Validation\Rules;

use phpDocumentor\Reflection\Types\Boolean;

interface ValidationRule {
	public function message(): string;

	public function isValid(string $field, array $data): Bool;
}