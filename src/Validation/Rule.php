<?php

namespace Jmarreros\Validation;

use Jmarreros\Exceptions\JmarrerosException;
use Jmarreros\Validation\Exceptions\RulesParseException;
use Jmarreros\Validation\Exceptions\UnknownRuleException;
use Jmarreros\Validation\Rules\Email;
use Jmarreros\Validation\Rules\LessThan;
use Jmarreros\Validation\Rules\Number;
use Jmarreros\Validation\Rules\Required;
use Jmarreros\Validation\Rules\RequiredWhen;
use Jmarreros\Validation\Rules\RequiredWith;
use Jmarreros\Validation\Rules\ValidationRule;
use ReflectionClass;

class Rule {
	private static array $rules = [];

	private static array $defaultRules = [
		Required::class,
		RequiredWith::class,
		RequiredWhen::class,
		Number::class,
		LessThan::class,
		Email::class
	];

	public static function LoadDefaultRules() {
		self::load( self::$defaultRules );
	}

	public static function nameOf( ValidationRule $rule ): string {
		$class = new ReflectionClass( $rule );

		return snake_case( $class->getShortName() );
	}

	public static function load( array $rules ): void {
		foreach ( $rules as $class ) {
			$className                = array_slice( explode( "\\", $class ), - 1 )[0];
			$ruleName                 = snake_case( $className );
			self::$rules[ $ruleName ] = $class;
		}
	}

	public static function email(): ValidationRule {
		return new Email;
	}

	public static function required(): ValidationRule {
		return new Required;
	}

	public static function requiredWith( string $withField ): ValidationRule {
		return new RequiredWith( $withField );
	}

	public static function number(): ValidationRule {
		return new Number;
	}

	public static function lessThan( int $number ): ValidationRule {
		return new LessThan( $number );
	}

	public static function requiredWhen( string $withField, string $withSymbol ): ValidationRule {
		return new RequiredWith( $withField );
	}

	public static function parseBasicRule( string $ruleName ): ValidationRule {
		$class                 = new ReflectionClass( self::$rules[ $ruleName ] );
		$constructorParameters = $class->getConstructor()?->getParameters() ?? [];

		if ( count( $constructorParameters ) > 0 ) {
			throw new RulesParseException( "Rule $ruleName required parameters" );
		}

		return $class->newInstance();
	}

	public static function parseRuleWithParameters( string $ruleName, string $params ): ValidationRule {
		$class                 = new ReflectionClass( self::$rules[ $ruleName ] );
		$constructorParameters = $class->getConstructor()?->getParameters() ?? [];
		$givenParams           = array_filter( explode( ",", $params ), fn( $param ) => ! empty( $param ) );

		if ( count( $givenParams ) !== count( $constructorParameters ) ) {
			throw new RulesParseException( sprintf( "Rule %s required %d, but %d were given: %s",
				$ruleName,
				count( $constructorParameters ),
				count( $givenParams ),
				$params
			) );
		}

		return $class->newInstance( ...$givenParams );
	}


	public
	static function from(
		string $str
	): ValidationRule {
		if ( strlen( $str ) === 0 ) {
			throw new RulesParseException( "Can't parse an empty string to rule" );
		}

		$ruleParts = explode( ":", $str );

		if ( ! array_key_exists( $ruleParts[0], self::$rules ) ) {
			throw new UnknownRuleException( "Rule $ruleParts[0] not found" );
		}

		if ( count( $ruleParts ) === 1 ) {
			return self::parseBasicRule( $ruleParts[0] );
		}

		[ $ruleName, $params ] = $ruleParts;

		return self::parseRuleWithParameters( $ruleName, $params );
	}

}