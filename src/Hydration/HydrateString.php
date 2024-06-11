<?php

namespace Codemastercarlos\Receipt\Hydration;

trait HydrateString
{
    public static function hydrateString(string $string): string
    {
        $stringWithTagsRemoved = self::removeAllTagsFromString($string);

        $stringWithSpecialCharactersRemoved = self::convertsSpecialCharactersToHtmlEntity($stringWithTagsRemoved);

        return trim($stringWithSpecialCharactersRemoved);
    }

    public static function removeAllTagsFromString(string $string): string
    {
        return strip_tags($string);
    }

    public static function convertsSpecialCharactersToHtmlEntity(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
    }
}
