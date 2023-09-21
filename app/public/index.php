<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../src/definitions.php');
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

require_once __DIR__ . '/../routes/api.php';
require_once __DIR__ . '/../routes/web.php';

$app->addRoutingMiddleware();

$errorSettings = $container->get('Config')->getErrorSettings();

$errorMiddleware = $app->addErrorMiddleware(
    $errorSettings['displayErrorDetails'],
    $errorSettings['logErrors'],
    $errorSettings['logErrorDetails']
);

$app->run();
