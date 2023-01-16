<?php

namespace Jmarreros\Test\Validation;

use Jmarreros\Validation\Rules\Email;
use Jmarreros\Validation\Rules\LessThan;
use Jmarreros\Validation\Rules\Number;
use Jmarreros\Validation\Rules\Required;
use Jmarreros\Validation\Rules\RequiredWhen;
use Jmarreros\Validation\Rules\RequiredWith;
use PHPUnit\Framework\TestCase;

class ValidationRulesTest extends TestCase {
	public function emails() {
		return [
			[ "test@test.com", true ],
			[ "antonio@mastermind.ac", true ],
			[ "test@testcom", false ],
			[ "test@test.", false ],
			[ "antonio@", false ],
			[ "antonio@.", false ],
			[ "antonio", false ],
			[ "@", false ],
			[ "", false ],
			[ null, false ],
			[ 4, false ],
		];
	}

	public function requiredData() {
		return [
			["", false],
			[null, false],
			[5, true],
			["test", true],
		];
	}

	/**
	 * @dataProvider emails
	 */
	public function test_email( $email, $expected ) {
		$data = [ 'email' => $email ];
		$rule = new Email();

		$this->assertEquals( $expected, $rule->isValid( 'email', $data ) );
	}

	/**
	 * @dataProvider requiredData
	 */
	public function test_required($value, $expected){
		$data = [ 'test' => $value ];
		$rule = new Required();

		$this->assertEquals( $expected, $rule->isValid( 'test', $data ) );
	}

	public function test_required_with(){
		$rule = new RequiredWith('other');
		$data = ['other' => 10, 'test' => 5];

		$this->assertTrue($rule->isValid( 'test', $data ) );

		$rule = new RequiredWith('other');
		$data = ['other' => 10, 'xxx' => 20];

		$this->assertFalse($rule->isValid( 'test', $data ) );
	}

	public function data_numbers(): array {
		return [
			["22", true],
			["da", false],
			["23ss", false],
			["-33", true],
			["-3dd", false],
		];
	}

	/**
	 * @dataProvider data_numbers
	 */
	public function test_is_numeric($value, $expected){
		$data = ['number' => $value];
		$number = new Number();
		$this->assertEquals($expected, $number->isValid('number', $data));
	}


	public function test_is_less_than(){
		$data = ['number' => 4];
		$number = new LessThan(10);

		$this->assertEquals(true, $number->isValid('number', $data));
	}


	public function test_is_required_when(){
		$rule = new RequiredWhen('other', '>');
		$data = ['other' => 50, 'test' => 5];

		$this->assertTrue($rule->isValid( 'test', $data ) );

		$rule = new RequiredWhen('other', '=');
		$data = ['other' => 10, 'test' => 10];

		$this->assertTrue($rule->isValid( 'test', $data ) );

		$rule = new RequiredWhen('other', '<');
		$data = ['other' => 10, 'test' => 100];

		$this->assertTrue($rule->isValid( 'test', $data ) );
	}
}