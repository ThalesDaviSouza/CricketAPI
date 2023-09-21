<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . '/../middlewares/jsonBodyParser.php';
require_once __DIR__ . '/../middlewares/authentication.php';
require_once __DIR__ . '/../middlewares/dataValidation.php';

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

// Add a new player

$app->post('/player/add', function (Request $request, Response $response) {
    $parsedBody = $request->getParsedBody();

    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->insert('Players')
        ->setValue('Name', '?')
        ->setValue('Team', '?')
        ->setValue('Category', '?')
        ->setParameter(1, $parsedBody['Name'])
        ->setParameter(2, $parsedBody['Team'])
        ->setParameter(3, $parsedBody['Category'])
    ;

    $result = $queryBuilder->executeStatement();

    $response->getBody()->write(json_encode($result));

    return $response->withHeader('Content-Type', 'application/json');

})
->add($jsonBodyParser)
->add($dataValidation)
->add($authentication);


// Update a Player

$app->put('/player/{id}', function (Request $request, Response $response, array $args) {
    $parsedBody = $request->getParsedBody();

    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->update('Players')
        ->set('Name', '?')
        ->set('Team', '?')
        ->set('Category', '?')
        ->where('id = ?')
        ->setParameter(1, $parsedBody['Name'])
        ->setParameter(2, $parsedBody['Team'])
        ->setParameter(3, $parsedBody['Category'])
        ->setParameter(4, $args['id'])
    ;

    $result = $queryBuilder->executeStatement();

    $response->getBody()->write(json_encode($result));

    return $response->withHeader('Content-Type', 'application/json');
})
->add($jsonBodyParser)
->add($dataValidation)
->add($authentication);

// Delete a Player

$app->delete('/player/{id}', function(Request $request, Response $response, array $args) {

    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->delete('Players')
        ->where('id = ?')
        ->setParameter(1, $args['id'])
    ;

    $result = $queryBuilder->executeStatement();

    $response->getBody()->write(json_encode($result));

    return $response->withHeader('content-type', 'application/json');
})
->add($jsonBodyParser)
->add($authentication);
