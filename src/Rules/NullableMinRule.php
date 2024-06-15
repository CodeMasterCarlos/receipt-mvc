<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class NullableMinRule implements Rule
{
    private readonly MinRule $rule;

    public function __construct(private readonly int $min)
    {
        $this->rule = new MinRule();
    }

    public function validate($value, $param = null): bool
    {
        if (empty($value)) {
            return true;
        }

        return $this->rule->validate($value, $this->min);
    }

    public function messageError($value, $param = null): string
    {
        return $this->rule->messageError($value, $this->min);
    }
}
