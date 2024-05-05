<?php

namespace Codemastercarlos\Receipt\Bootstrap;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as LoggerMonolog;

trait Logger
{
    private function logger(): LoggerMonolog
    {
        $logger = new LoggerMonolog('web');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/record.log', Level::Debug));
        $logger->pushProcessor(function ($record) {
            $record->extra['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
            $record->extra['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
            $record->extra['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
            $record->extra['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

            return $record;
        });
        return $logger;
    }
}