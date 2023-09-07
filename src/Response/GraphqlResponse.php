<?php

namespace GraphQL\Response;

class GraphqlResponse
{
    public function __construct(
        private readonly int $statusCode,
        private readonly array $data = [],
        private readonly array $errors = []
    ) {
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isSuccessful()
    {
        return $this->statusCode === 200 && empty($this->errors);
    }
}