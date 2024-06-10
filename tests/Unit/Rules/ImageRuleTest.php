<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Rules;

use Codemastercarlos\Receipt\Rules\ImageRule;
use Nyholm\Psr7\UploadedFile;
use PHPUnit\Framework\TestCase;

class ImageRuleTest extends TestCase
{
    public function testValidateRuleIsImage(): void
    {
        $imagePath = __DIR__ . '/../../images/lies-of-p.jpg';
        $imageContent = file_get_contents($imagePath);

        $image = new UploadedFile(
            $imagePath,
            strlen($imageContent),
            UPLOAD_ERR_OK,
            basename($imagePath),
            'image/jpg'
        );

        $rule = new ImageRule();

        $validation = $rule->validate($image);

        self::assertTrue($validation);
    }
}
