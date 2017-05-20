<?php

$container = $app->getContainer();
$container['sparql'] = function ($container) {
    return new \App\Repository\SparqlRepository();
};