<?php

use App\Transformer\Serializer;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/product-individuals', function () {

    $this->post('', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        $sparql = $this->get('sparql');

        $checkedInds = Serializer::serialize($parsedBody['checkedInds']);
        $res = $sparql->getProductsFromFuncIndividuals($checkedInds, $parsedBody['price'], $parsedBody['brand']);

        return $response->withJson([
            'next' => count($res) > 0,
            'products' => Serializer::deserialize($res)
        ]);
    });
});

$app->post('/product-details', function (Request $request, Response $response) {
    $parsedBody = $request->getParsedBody();
    $sparql = $this->get('sparql');

    $name = Serializer::serialize([$parsedBody['name']])[0];
    $res = $sparql->getProductDetails($name);

    return $response->withJson([
        'details' => Serializer::deserialize($res)
    ]);
});