<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Rules;

use Codemastercarlos\Receipt\Rules\MaxRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MaxRuleTest extends TestCase
{
    #[DataProvider('validNameProvider')]
    public function testSuccessfullyValidateNameMaxLetter($name, $max): void
    {
        $rule = new MaxRule();

        $validation = $rule->validate($name, $max);

        static::assertTrue($validation);
    }

    #[DataProvider('invalidNameProvider')]
    public function testFailureValidateNameMaxLetter($name, $max): void
    {
        $rule = new MaxRule();

        $validation = $rule->validate($name, $max);

        static::assertFalse($validation);
    }

    public function testNameWithAccentedLetterMustCountCorrectly(): void
    {
        $rule = new MaxRule();

        $validation = $rule->validate("João", 4);

        static::assertTrue($validation);
    }

    public function testValidateNameWithDefaultMaximumNumberOfCharacters(): void
    {
        $rule = new MaxRule();

        $textLongerThan255Characters = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis ipsum, rhoncus maximus dui. Proin gravida ipsum nisi, in sagittis erat dapibus sed. Nullam dapibus odio arcu, vulputate condimentum tellus tincidunt at. Donec condimentum ante vi";

        $validation = $rule->validate($textLongerThan255Characters);

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
            ["Ana", 2],
            ["Luan", 3],
            ["Maria", 4],
        ];
    }
}
