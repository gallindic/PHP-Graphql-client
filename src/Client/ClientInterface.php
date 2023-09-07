<?php

namespace GraphQL\Client;

interface ClientInterface
{
    public function post(string $uri, array $data, array $headers = [], ): mixed;

    public function get(string $uri, array $headers = []);
}