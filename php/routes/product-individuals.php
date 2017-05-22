<?php

use App\Transformer\Serializer;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/product-individuals', function () {

    $this->post('', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        $sparql = $this->get('sparql');

        $checkedInds = Serializer::serialize($parsedBody['checkedInds']);
        $res = [];

        foreach ($checkedInds as $checkedInd) {
            $res = array_merge($res, $sparql->getProductsFromFuncIndividual($checkedInd));
        }

        return $response->withJson([
            'next' => count($res) > 0,
            'products' => Serializer::deserialize($res)
        ]);
    });
});