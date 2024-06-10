<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Rules;

use Codemastercarlos\Receipt\Rules\EmailRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EmailRuleTest extends TestCase
{
    #[DataProvider('validEmailProvider')]
    public function testSuccessfullyValidateEmail($email): void
    {
        $rule = new EmailRule();

        $validation = $rule->validate($email);

        static::assertTrue($validation);
    }

    #[DataProvider('invalidEmailProvider')]
    public function testFailureValidateEmail($email): void
    {
        $rule = new EmailRule();

        $validation = $rule->validate($email);

        static::assertFalse($validation);
    }

    public static function validEmailProvider(): array
    {
        return [
            ["example1@gmail.com"],
            ["example2@yahoo.com"],
            ["codemastercarlos@outlook.com"],
        ];
    }

    public static function invalidEmailProvider(): array
    {
        return [
            ["example1gmailcom"],
            ["example1gmail.com"],
            ["example@1gmailcom"],
            ["example1@gmail.c"],
        ];
    }
}
