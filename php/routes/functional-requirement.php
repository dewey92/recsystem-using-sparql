<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/functional-requirement', function () {
    $this->get('', function (Request $request, Response $response) {
        $sparql = new \App\Repository\SparqlRepository();
        $res = $sparql->getAllFunctionalRequirements();

        return $response->withJson(\App\Transformer\RequirementTransformer::transform($res));
    });
});