<?php

return [
    'required' => \Codemastercarlos\Receipt\Rules\RequiredRule::class,
    'min' => \Codemastercarlos\Receipt\Rules\MinRule::class,
    'max' => \Codemastercarlos\Receipt\Rules\MaxRule::class,
    'image' => \Codemastercarlos\Receipt\Rules\ImageRule::class,
    'email' => \Codemastercarlos\Receipt\Rules\EmailRule::class,
];
