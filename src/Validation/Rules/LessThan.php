<?php

namespace Jmarreros\Validation\Rules;

class LessThan implements ValidationRule {
	protected int $number;

	public function __construct(int $number) {
		$this->number = $number;
	}

	public function message(): string {
		return "El número es menor que $this->number";
	}

	public function isValid( string $field, array $data ): bool {
		$number = $data[$field];
		return $number < $this->number;
	}
}