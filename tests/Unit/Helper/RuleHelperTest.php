<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Helper;

use Codemastercarlos\Receipt\Bootstrap\Config\SettingsFileConfig;
use Codemastercarlos\Receipt\Exception\InvalidValidationException;
use Codemastercarlos\Receipt\Helper\RuleHelper;
use Codemastercarlos\Receipt\Rules\EmailRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RuleHelperTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        SettingsFileConfig::addSettingsFilesInSuperGlobal();
    }

    /**
     * @throws InvalidValidationException
     */
    #[DataProvider('providerEmail')]
    public function testRuleRegisteredTheConfigFilesShouldSuccessful($email): void
    {
        $helper = new RuleHelper('email', 'email', $email);

        $validation = $helper->isValid();

        static::assertTrue($validation);
    }

    #[DataProvider('providerEmail')]
    public function testRuleNotRegisteredConfigFilesShouldFail($email): void
    {
        $this->expectException(InvalidValidationException::class);

        new RuleHelper('email-no-registered-rule', 'email', $email);
    }

    /**
     * @throws InvalidValidationException
     */
    #[DataProvider('providerEmail')]
    public function testRuleWithDirectRuleShouldSuccessful($email): void
    {
        $helper = new RuleHelper(new EmailRule(), 'email', $email);

        $validation = $helper->isValid();

        static::assertTrue($validation);
    }

    public function testRuleWithDefaultValueMustNull(): void
    {
        $helper = new RuleHelper('required', 'email', null);

        $validation = $helper->isValid();

        static::assertFalse($validation);
    }

    public function testRuleMessageShouldReturnFormattedErrorMessage(): void
    {
        $helper = new RuleHelper('required', 'email', null);

        $message = $helper->messageError();

        static::assertSame("O campo e-mail é obrigatório", $message);
    }

    public static function providerEmail(): array
    {
        return [
            ['codemastercarlos@outlook.com'],
        ];
    }
}
