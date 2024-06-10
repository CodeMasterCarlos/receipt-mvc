<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class MinRule implements Rule
{
    public function validate($value, $param = 1): bool
    {
        return mb_strlen($value) >= $param;
    }

    public function messageError($value, $param = 1): string
    {
        $min = $param;
        return "O campo :attr deve ter pelos menos $min caracteres";
    }
}
