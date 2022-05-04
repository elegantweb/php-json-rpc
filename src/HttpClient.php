<?php

namespace Elegant\JsonRpc;

use GuzzleHttp\Client as GuzzleHttpClient;

class HttpClient extends Client
{
    protected $httpClient;

    public function __construct($uri, array $options = [])
    {
        $options['base_uri'] = $uri;

        $this->httpClient = new GuzzleHttpClient($options);
    }
    
    public function getHttpClient(): GuzzleHttpClient
    {
        return $this->httpClient;
    }

    public function sendRequest($body)
    {
        $headers = [];
        $headers['Content-Type'] = 'application/json';
        $headers['Connection-Type'] = 'close';

        return $this->httpClient->request('POST', '', compact('headers', 'body'))->getBody();
    }
}
