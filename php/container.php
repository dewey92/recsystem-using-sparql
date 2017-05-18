<?php

$container = $app->getContainer();
$container['sparql'] = function ($container) {
    require_once('Infrastructure/sparqllib.php');

    $url = 'http://localhost:3030/ds/sparql';
    $db = sparql_connect($url);
    if (!$db) {
        print sparql_errno() . ": " . sparql_error() . "\n";
        exit;
    }
};