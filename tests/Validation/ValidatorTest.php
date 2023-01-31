<?php

namespace Jmarreros\Test\Validation;

use Jmarreros\Validation\Exceptions\ValidationException;
use Jmarreros\Validation\Rule;
use Jmarreros\Validation\Rules\Email;
use Jmarreros\Validation\Rules\LessThan;
use Jmarreros\Validation\Rules\Number;
use Jmarreros\Validation\Rules\Required;
use Jmarreros\Validation\Rules\RequiredWith;
use Jmarreros\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase {

	// Se ejecuta antes de cada test
	protected function setUp(): void {
		Rule::LoadDefaultRules();
	}

	public function test_basic_validation_passes() {
		$data = [
			"email" => "test@test.com",
			"other" => 2,
			"num"   => 3,
			"foo"   => 5,
			"bar"   => 4
		];

		$rules = [
			"email" => new Email(),
			"other" => new Required(),
			"num"   => new Number(),
		];

		$expected = [
			"email" => "test@test.com",
			"other" => 2,
			"num"   => 3,
		];

		$v = new Validator( $data );

		$this->assertEquals( $expected, $v->validate( $rules ) );
	}

	public function test_throws_validation_exception_on_invalid_data() {
		$this->expectException( ValidationException::class );
		$v = new Validator( [ "test" => "test" ] );
		$v->validate( [ "test" => new Number() ] );
	}

	/**
	 * @depends test_basic_validation_passes
	 */
	public function test_multiple_rules_validation() {
		$data = [ "age" => 20, "num" => 3, "foo" => 5 ];

		$rules = [
			"age" => new LessThan( 100 ),
			"num" => [ new RequiredWith( "age" ), new Number() ],
		];

		$expected = [ "age" => 20, "num" => 3 ];

		$v = new Validator( $data );

		$this->assertEquals( $expected, $v->validate( $rules ) );
	}

	public function test_overrides_error_message_correctly() {
		$data  = [ "email" => "test@", "num1" => "not a number" , "num2" => "ss"];
		$rules = [
			"email" => "email",
			"num1"  => "number",
			"num2"  => [ "required", "number" ],
		];

		$messages = [
			"email" => [ "email" => "Email test message" ],
			"num1"  => [ "number" => "Test number message" ],
			"num2"  => [ "number" => "Test number message again" ]
		];

		$v = new Validator( $data );

		try {
			$v->validate( $rules, $messages );
			$this->fail( "Did not throw validation exception" );
		} catch ( ValidationException $e ) {
			$this->assertEquals( $messages, $e->errors() );
		}
	}
}