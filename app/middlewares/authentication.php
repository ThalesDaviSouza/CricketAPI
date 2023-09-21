<?php
use Psr\Http\Message\ServerRequestInterface;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;

$authentication = function (Request $request, RequestHandler $handler)
{
    $userName = $request->getHeaderLine('X-API-User');
    $apiKey = $request->getHeaderLine('X-API-Key');
    
    if(!$userName || !$apiKey){
        return sendErrorResponse([
            'msg' => 'Specify a username and apikey for authentication'
        ]);
    }

    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('ApiKey')
        ->from('Users')
        ->where('Username = ?')
        ->setParameter(1, $userName)
    ;

    $result = $queryBuilder->executeQuery()->fetchAssociative();

    if(!$result){
        return sendErrorResponse([
            'msg' => 'username does not exist',
        ]);
    }

    if(array_key_exists('ApiKey', $result)){
        $hashedApiKey = $result['ApiKey']; 
    }else{
        return sendErrorResponse([
            'msg' => 'username does not exist',
        ]);
    }

    if(!password_verify($apiKey, $hashedApiKey)){
        return sendErrorResponse([
            'msg' => 'Invalid Api Key'
        ]);
    }

    $response = $handler->handle($request);
    return $response;
};

function sendErrorResponse(array $error){
    $response = new Response();
    $response->getBody()->write(json_encode($error));

    $newResponse = $response->withStatus(401);

    return $newResponse->withHeader('Content-Type', 'application/json');
}
