<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

# All the api routes

// Home

$app->get('/', function (Request $request, Response $response){
    $response_array = [
        'message' => 'Welcome to the Cricket Player API',
    ];

    $response_string = json_encode($response_array);

    $response->getBody()->write($response_string);

    return $response->withHeader('Content-Type', 'application/json');
});

// Get all Players

$app->get('/players/', function(Request $request, Response $response){
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('id', 'Name', 'Team', 'Category')
        ->from('Players')
    ;

    $results = $queryBuilder->executeQuery()->fetchAllAssociative();

    $response->getBody()->write(json_encode($results));

    return $response->withHeader('Content-Type', 'application/json');
});

// Get a single Player

$app->get('/player/{id}', function (Request $request, Response $response, array $args) {
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('id', 'Name', 'Team', 'Category')
        ->from('Players')
        ->where('id = ?')
        ->setParameter(1, $args['id'])
    ;

    $result = $queryBuilder->executeQuery()->fetchAssociative();

    $response->getBody()->write(json_encode($result));

    return $response->withHeader('Content-Type', 'application/json');
});

