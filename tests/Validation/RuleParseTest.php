<?php

namespace Jmarreros\Test\Validation;

use Jmarreros\Validation\Rule;
use Jmarreros\Validation\Rules\Email;
use Jmarreros\Validation\Rules\Number;
use Jmarreros\Validation\Rules\Required;
use PHPUnit\Framework\TestCase;

class RuleParseTest extends TestCase {
    protected function setUp(): void {
        Rule::loadDefaultRules();
    }

    public function basicRules() {
        return [
            [Email::class, "email"],
            [Required::class, "required"],
            [Number::class, "number"],
        ];
    }

    /**
     * @dataProvider basicRules
     */
    public function test_parse_basic_rules($class, $name) {
        $this->assertInstanceOf($class, Rule::from($name));
    }
}
