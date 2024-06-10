<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Helper;

use Codemastercarlos\Receipt\Bootstrap\Config\SettingsFileConfig;
use Codemastercarlos\Receipt\Exception\InvalidValidationException;
use Codemastercarlos\Receipt\Helper\ValidationHelper;
use Codemastercarlos\Receipt\Rules\EmailRule;
use InvalidArgumentException;
use Nyholm\Psr7\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        SettingsFileConfig::addSettingsFilesInSuperGlobal();
    }

    /**
     * @throws InvalidValidationException
     */
    #[DataProvider('providerEmailAndPasswordSuccess')]
    public function testValidateValidEmailAndPasswordWithSimpleRules($params): void
    {
        $validation = new ValidationHelper($params, [
            'email' => ['required', 'min:5', 'max:255'],
            'password' => ['required', 'min:8', 'max:255'],
        ]);

        static::assertSame($params['email'], $validation->getAttribute('email'));
        static::assertSame($params['password'], $validation->getAttribute('password'));
    }

    #[DataProvider('providerEmailAndPasswordFailure')]
    public function testValidateInvalidEmailAndPasswordWithSimpleRules($params): void
    {
        $this->expectException(InvalidValidationException::class);

        new ValidationHelper($params, [
            'email' => ['required', 'min:5', 'max:255'],
            'password' => ['required', 'min:8', 'max:255'],
        ]);
    }

    /**
     * @throws InvalidValidationException
     */
    #[DataProvider('providerEmailAndPasswordSuccess')]
    public function testValidateValidEmailAndPasswordWithDirectRules($params): void
    {
        $validation = new ValidationHelper($params, [
            'email' => ['required', 'min:5', 'max:255', new EmailRule()],
            'password' => ['required', 'min:8', 'max:255'],
        ]);

        static::assertSame($params['email'], $validation->getAttribute('email'));
        static::assertSame($params['password'], $validation->getAttribute('password'));
    }

    /**
     * @throws InvalidValidationException
     */
    #[DataProvider('providerEmailAndPasswordFailure')]
    public function testValidateNonExistentParameterFailureWithRequiredRule($params): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ValidationHelper($params, [
            'user-attribute-no-exist' => ['required'],
        ]);
    }

    /**
     * @throws InvalidValidationException
     */
    public function testValidateParametersAndEmptyValidationsMustResultInNull(): void
    {
        $validation = new ValidationHelper([], []);

        static::assertNull($validation->getAttribute('email'));
    }

    /**
     * @throws InvalidValidationException
     */
    public function testValidateUploadByVerifyingIsImage(): void
    {
        $imagePath = 'F:\www\receipt-mvc\tests\images\lies-of-p.jpg';
        $imageContent = file_get_contents($imagePath);

        $params['image'] = new UploadedFile(
            $imagePath,
            strlen($imageContent),
            UPLOAD_ERR_OK,
            basename($imagePath),
            'image/jpg'
        );

        $validation = new ValidationHelper($params, [
            'image' => ['required', 'image'],
        ]);

        static::assertSame($params['image'], $validation->getAttribute('image'));
    }

    public function testValidateUploadByVerifyingIsImageBySendingDocument(): void
    {
        $this->expectException(InvalidValidationException::class);

        $imagePath = 'F:\www\receipt-mvc\tests\images\lies-of-p.txt';
        $imageContent = file_get_contents($imagePath);

        $params['image'] = new UploadedFile(
            $imagePath,
            strlen($imageContent),
            UPLOAD_ERR_OK,
            basename($imagePath),
            'image/jpg'
        );

        new ValidationHelper($params, [
            'image' => ['required', 'image'],
        ]);
    }

    public static function providerEmailAndPasswordSuccess(): array
    {
        return [
            [
                ['email' => 'codemastercarlos@outlook.com', 'password' => '12345678'],
            ],
        ];
    }

    public static function providerEmailAndPasswordFailure(): array
    {
        return [
            [
                ['email' => 'c@o.', 'password' => '12345678'],
            ],
            [
                ['email' => 'codemastercarlos@outlook.com', 'password' => '1234567'],
            ],
        ];
    }
}
