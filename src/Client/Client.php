<?php

namespace GraphQL\Client;

use GraphQL\Exceptions\ClientException;

class Client implements ClientInterface
{
    protected $ch;

    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    public function get(string $uri, array $headers = [])
    {
        $this->prepareDefaultConfig($uri, $headers);
        curl_setopt($this->ch, CURLOPT_POST, false);

        return [];
    }

    public function post(string $uri, array $data, array $headers = []): mixed
    {
        $this->prepareDefaultConfig($uri, $headers);

        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));

        return $this->executeRequest();
    }

    protected function prepareDefaultConfig(string $url, array $headers): void
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL Verification - testing purposes
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array_merge($this->getDefaultHeaders(), $headers));
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Content-Type: application/json',
        ];
    }

    protected function executeRequest(): mixed
    {
        $response = curl_exec($this->ch);

        if (curl_errno($this->ch)) {
            throw new ClientException(curl_error($this->ch));
        }

        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        return [$statusCode, json_decode($response, true)];
    }
}