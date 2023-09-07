<?php

use GraphQL\Elements\Variable;
use GraphQL\GraphqlClient;
use GraphQL\Operations\Query;
use GraphQL\Enums\VariableType;

require_once 'vendor/autoload.php';

$graphqlClient = new GraphqlClient('https://countries.trevorblades.com/', array());

$query = (new Query('continent'))
    ->args([
        'code' => 'AF'
    ])
    ->fields([
        'name',
        'code',
        (new Query('countries'))
            ->fields([
                'name',
                'code'
            ])
    ]);

$variables = [
    'code' => 'AF'
];

$gqlResponse = $graphqlClient->execute($query, []);
var_dump($gqlResponse->getData(), $gqlResponse->getErrors());