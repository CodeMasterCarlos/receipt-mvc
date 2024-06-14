<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class DateRule implements Rule
{
    public function validate($value, $param = null): bool
    {
        return preg_match("/^\d{4}-\d{2}-\d{2}/", $value);
    }

    public function messageError($value, $param = null): string
    {
        return "Informe um data válida.";
    }
}
