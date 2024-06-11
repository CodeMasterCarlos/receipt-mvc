<?php

namespace Codemastercarlos\Receipt\Hydration;

use DateTimeImmutable;

trait HydrateDate
{
    public static function dateToStringFormatEua(DateTimeImmutable $date): string
    {
        return $date->format("Y-m-d");
    }
}
