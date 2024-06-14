<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class IntegerReceiptRule implements Rule
{
    public function validate($value, $param = null): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    public function messageError($value, $param = null): string
    {
        return "Informe um comprovante válido.";
    }
}
