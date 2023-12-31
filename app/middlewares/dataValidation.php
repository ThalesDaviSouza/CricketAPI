<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as Response;
use JsonSchema\Validator as Validator;


$dataValidation = function (Request $request, RequestHandler $handler) {
    
    $jsonSchema = <<< 'JSON'
    {
        "type": "object",
        "properties": {
            "Name": {"type": "string"},
            "Team": {"type": "string"},
            "Category": {"type": "string"}
        },
        "required": ["Name", "Team", "Category"] 
    }
    JSON;

    $jsonSchameObj = json_decode($jsonSchema);

    $validator = new Validator();
    $data = $request->getParsedBody();
    
    $dataObject = json_decode(json_encode($data));

    $validator->validate($dataObject, $jsonSchameObj);

    if($validator->isValid()){
        $response = $handler->handle($request);
        return $response;
    }else{
        $response = new Response();
        $response->getBody()->write(json_encode($validator->getErrors()));

        $newResponse = $response->withStatus(400);

        return $newResponse->withHeader('Content-type', 'application/json');
    }

};

