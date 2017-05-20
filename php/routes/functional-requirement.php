<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/functional-requirement', function () {
    $this->get('', function (Request $request, Response $response) {
        $sparql = $this->get('sparql');
        $res = $sparql->getAllFunctionalRequirements();

        return $response->withJson(\App\Transformer\Serializer::deserialize($res));
    });

    $this->post('', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        $sparql = $this->get('sparql');

        $checkedReqs = \App\Transformer\Serializer::serialize($parsedBody['checkedReqs']);

        $res = $sparql->getTreeFromFunc($checkedReqs);
        return $response->withJson([
            'next' => true,
            'subclasses' => \App\Transformer\Serializer::deserialize($res)
        ]);
    });
});