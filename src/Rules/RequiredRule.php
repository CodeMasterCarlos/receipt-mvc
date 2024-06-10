<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class RequiredRule implements Rule
{
    public function validate($value = null, $param = null): bool
    {
        return $value !== null;
    }

    public function messageError($value, $param = null): string
    {
        return "O campo :attr é obrigatório";
    }
}
