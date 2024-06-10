<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class MaxRule implements Rule
{
    public function validate($value, $param = 255): bool
    {
        return mb_strlen($value) <= $param;
    }

    public function messageError($value, $param = 255): string
    {
        $max = $param;
        return "O campo :attr não pode conter mais de $max caracteres.";
    }
}
