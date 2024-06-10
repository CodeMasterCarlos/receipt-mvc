<?php

namespace Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation;

interface Rule
{
    public function validate($value, $param = null): bool;

    public function messageError($value, $param = null): string;
}
