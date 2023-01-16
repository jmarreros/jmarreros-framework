<?php

namespace Jmarreros\Validation\Rules;

class RequiredWhen implements ValidationRule {
	protected string $withField;
	protected string $symbol;

	public function __construct( string $withField, string $symbol ) {
		$this->withField = $withField;
		$this->symbol = $symbol;
	}

	public function message(): string {
		return "This field should be $this->symbol than $this->withField";
	}

	public function isValid( string $field, array $data ): bool {
		if ( isset( $data[ $field ] ) && $data[ $field ] != '' ) {
			$number = $data[ $field ];

			return match ( $this->symbol ) {
				"=" => $number == $data[$this->withField],
				">" => $number < $data[$this->withField],
				"<" => $number > $data[$this->withField],
			};

		}

		return true;
	}
}