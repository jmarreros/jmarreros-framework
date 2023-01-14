<?php

namespace Jmarreros\Test\Validation;

use Jmarreros\Validation\Rules\Email;
use Jmarreros\Validation\Rules\Required;
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

	//TODO:
	// RequiredWhen('other', '>', 5)
	//
}