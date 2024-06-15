<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class NullableDateRule implements Rule
{
    private readonly DateRule $rule;

    public function __construct()
    {
        $this->rule = new DateRule();
    }

    public function validate($value, $param = null): bool
    {
        if (empty($value)) {
            return true;
        }

        return $this->rule->validate($value);
    }

    public function messageError($value, $param = null): string
    {
        return $this->rule->messageError($value);
    }
}
