<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Helper;

use Codemastercarlos\Receipt\Helper\HydrateHelper;
use PHPUnit\Framework\TestCase;

class HydrateHelperTest extends TestCase
{
    public function testHydrateStringByRemovingTagsAndSpecialCharacters(): void
    {
        $string = "Carlos Alexandre <a href='https://carlosalexandre.com.br'>Test</a> <script>alert('XSS');</script>";

        $stringHydrate = HydrateHelper::hydrateString($string);

        static::assertSame("Carlos Alexandre Test alert(&#039;XSS&#039;);", $stringHydrate);
    }
}
