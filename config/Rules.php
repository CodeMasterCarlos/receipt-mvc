<?php

return [
    'required' => \Codemastercarlos\Receipt\Rules\RequiredRule::class,
    'min' => \Codemastercarlos\Receipt\Rules\MinRule::class,
    'max' => \Codemastercarlos\Receipt\Rules\MaxRule::class,
    'image' => \Codemastercarlos\Receipt\Rules\ImageRule::class,
    'email' => \Codemastercarlos\Receipt\Rules\EmailRule::class,
    'int-receipt' => \Codemastercarlos\Receipt\Rules\IntegerReceiptRule::class,
    'date' => \Codemastercarlos\Receipt\Rules\DateRule::class,
];
