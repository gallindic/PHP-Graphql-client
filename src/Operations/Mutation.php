<?php

namespace GraphQL\Operations;

class Mutation extends GraphqlOperation
{
    public function __construct(
        protected ?string $fieldName = null,
        protected ?string $operationAlias = null
    ) {
        parent::__construct($fieldName, $operationAlias);
    }

    public function hasSetRequired(): bool
    {
        return count($this->selectFields) > 0;
    }

    public function getOperationType(): string
    {
        return 'mutation';
    }
}