<?php

use Codemastercarlos\Receipt\Bootstrap\Bootstrap;
use Codemastercarlos\Receipt\Bootstrap\Config\SettingsFileConfig;
use Codemastercarlos\Receipt\Bootstrap\HttpDiContainer;
use Codemastercarlos\Receipt\Bootstrap\HttpError;
use Codemastercarlos\Receipt\Bootstrap\Logger;
use Codemastercarlos\Receipt\Bootstrap\Provider\BackPageExceptionProvider;
use Codemastercarlos\Receipt\Bootstrap\Provider\FinishRequestProvider;
use Codemastercarlos\Receipt\Exception\BackPageException;
use Psr\Container\ContainerInterface;

$_SESSION ?? session_start();
session_regenerate_id();

date_default_timezone_set("America/Sao_Paulo");

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $routes = require __DIR__ . '/../routes/routes.php';

    SettingsFileConfig::addSettingsFilesInSuperGlobal();

    $builder = require __DIR__ . '/../dependencies/DiContainer.php';

    /** @var ContainerInterface $container */
    $diContainer = $builder->build();

    new Bootstrap(
        new HttpDiContainer($diContainer, $routes, $GLOBALS['middlewares'])
    );

    new FinishRequestProvider();
} catch (BackPageException $e) {
    new BackPageExceptionProvider();
} catch (Throwable $e) {
    $logger = new Logger();
    $logger->logger()->error($e->getMessage(), ["exception" => $e]);
    new Bootstrap(
        new HttpError($e)
    );
}
