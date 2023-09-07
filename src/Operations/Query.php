<?php

namespace GraphQL\Operations;

class Query extends GraphqlOperation
{
    public function __construct(
        protected ?string $operationName = null,
        protected ?string $operationAlias = null
    ) {
        parent::__construct($operationName, $operationAlias);
    }

    public function hasSetRequired(): bool
    {
        return count($this->selectFields) > 0;
    }

    public function getOperationType(): string
    {
        return 'query';
    }
}