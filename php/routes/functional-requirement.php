<?php

use App\Transformer\Serializer;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/functional-requirement', function () {
    $this->get('', function (Request $request, Response $response) {
        $sparql = $this->get('sparql');
        $res = $sparql->getAllFunctionalRequirements();

        return $response->withJson(Serializer::deserialize($res));
    });

    $this->post('', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        $sparql = $this->get('sparql');

        $checkedReqs = Serializer::serialize($parsedBody['checkedReqs']);

        $res = $sparql->getIndividualsFromFunc($checkedReqs);
        return $response->withJson([
            'next' => true,
            'individuals' => Serializer::deserialize($res)
        ]);
    });

    $this->get('/lala', function (Request $request, Response $response) {
        $sparql = $this->get('sparql');

        $inds = ['Desain_untuk_Audio', 'Desain_untuk_Animasi_Bergerak', 'Menonton_Video_Full_HD_Offline'];
        $res = [];

        foreach ($inds as $ind) {
            $res = array_merge($res, $sparql->getProductsFromFuncIndividual($ind));
        }

        return $response->withJson([
            'next' => count($res) > 0,
            'products' => Serializer::deserialize($res)
        ]);
    });
});