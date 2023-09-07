<?php

namespace GraphQL;

use GraphQL\Client\Client;
use GraphQL\Client\ClientInterface;
use GraphQL\Builder\QueryBuilder;
use GraphQL\Operations\GraphqlOperation;
use GraphQL\Response\GraphqlResponse;

class GraphqlClient
{
    protected ClientInterface $httpClient;

    protected QueryBuilder $queryBuilder;

    public function __construct(
        protected string $endpointUrl,
        protected array $httpHeaders = []
    ) {
        $this->httpClient = new Client();
        $this->queryBuilder = new QueryBuilder();
    }

    public function buildRawQuery(GraphqlOperation $operation): string
    {
        return $this->queryBuilder->build($operation);
    }

    public function execute(GraphqlOperation $operation, array $variables): ?GraphqlResponse
    {
        $rawQuery = $this->buildRawQuery($operation);
        print_r($rawQuery);

        return $this->executeRaw($rawQuery, $variables);
    }

    public function executeRaw(string $rawQuery, array $variables): GraphqlResponse
    {
        [$statusCode, $response] = $this->httpClient->post(
            $this->endpointUrl,
            [
                'query' => $rawQuery,
                'variables' => $variables,
            ],
            $this->httpHeaders
        );

        return new GraphqlResponse($statusCode, $response['data'] ?? [], $response['errors'][0] ?? []);
    }
}