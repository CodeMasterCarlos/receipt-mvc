<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class EmailRule implements Rule
{
    public function validate($value, $param = null): bool
    {
        return preg_match("/[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}/", $value);
    }

    public function messageError($value, $param = null): string
    {
        return "O campo :attr deve ser um e-mail válido";
    }
}
