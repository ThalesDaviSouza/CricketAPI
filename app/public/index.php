<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

// Routes
$app->get('/', function (Request $request, Response $response){
    $response_array = [
        'message' => 'Welcome to the Cricket Player API',
    ];

    $response_string = json_encode($response_array);

    $response->getBody()->write($response_string);

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
