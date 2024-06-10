<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Rules;

use Codemastercarlos\Receipt\Rules\RequiredRule;
use PHPUnit\Framework\TestCase;

class RequiredRuleTest extends TestCase
{
    public function testValidationWithEmptyRequiredValue(): void
    {
        $rule = new RequiredRule();

        $validation = $rule->validate();

        static::assertFalse($validation);
    }

    public function testValidationWithRequiredValue(): void
    {
        $rule = new RequiredRule();

        $validation = $rule->validate("codemastercarlos");

        static::assertTrue($validation);
    }
}
