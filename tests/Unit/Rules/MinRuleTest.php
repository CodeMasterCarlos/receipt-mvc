<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Rules;

use Codemastercarlos\Receipt\Rules\MinRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MinRuleTest extends TestCase
{
    #[DataProvider('validNameProvider')]
    public function testSuccessfullyValidateNameMinLetters($name, $min): void
    {
        $rule = new MinRule();

        $validation = $rule->validate($name, $min);

        static::assertTrue($validation);
    }

    #[DataProvider('invalidNameProvider')]
    public function testFailureValidateNameMinLetters($name, $min): void
    {
        $rule = new MinRule();

        $validation = $rule->validate($name, $min);

        static::assertFalse($validation);
    }

    public function testNameWithAccentedLetterMustCountCorrectly(): void
    {
        $rule = new MinRule();

        $validation = $rule->validate("João", 4);

        static::assertTrue($validation);
    }

    public function testValidateNameWithDefaultMinimumNumberOfCharacters(): void
    {
        $rule = new MinRule();

        $validation = $rule->validate("");

        static::assertFalse($validation);
    }

    public static function validNameProvider(): array
    {
        return [
            ["Ana", 3],
            ["Luan", 4],
            ["Maria", 5],
        ];
    }

    public static function invalidNameProvider(): array
    {
        return [
            ["Ana", 4],
            ["Luan", 5],
            ["Maria", 6],
        ];
    }
}
