<?php

namespace Jmarreros\Validation\Rules;

class Number implements ValidationRule {

	public function message(): string {
		return "Enter a valida number";
	}

	public function isValid( string $field, array $data ): bool {
		return is_numeric($data[$field]);
	}
}