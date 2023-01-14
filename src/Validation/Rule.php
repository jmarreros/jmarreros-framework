<?php

namespace Jmarreros\Validation;

use Jmarreros\Validation\Rules\Email;
use Jmarreros\Validation\Rules\Required;
use Jmarreros\Validation\Rules\RequiredWith;
use Jmarreros\Validation\Rules\ValidationRule;

class Rule {
	public static function email(): ValidationRule {
		return new Email;
	}

	public static function required(): ValidationRule{
		return new Required;
	}

	public static function requiredWith(string $withField):ValidationRule{
		return new RequiredWith($withField);
	}

}