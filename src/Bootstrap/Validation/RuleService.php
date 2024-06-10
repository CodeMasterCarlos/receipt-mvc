<?php

namespace Codemastercarlos\Receipt\Bootstrap\Validation;

use Codemastercarlos\Receipt\Exception\InvalidValidationException;
use InvalidArgumentException;

class RuleService
{
    /**
     * @throws InvalidValidationException
     */
    public static function getRule(string $rule)
    {
        $classRule = $GLOBALS['rules'][$rule] ?? false;

        if ($classRule === false) {
            throw new InvalidValidationException("A rule $rule não existe");
        }

        return $classRule;
    }

    public static function getRuleAttributeInNaturalLanguage(string $attr): ?string
    {
        $langAttribute = $GLOBALS['lang'][$attr] ?? false;

        if ($langAttribute === false) {
            throw new InvalidArgumentException("O atributo $attr não foi encontrado na lista em src/config/Lang.php");
        }

        return $langAttribute;
    }
}
