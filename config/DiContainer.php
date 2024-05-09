<?php

use Codemastercarlos\Receipt\Bootstrap\PdoClass;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions([
    PDO::class => static function () {
        return PdoClass::getPdo();
    },
]);

return $builder;
