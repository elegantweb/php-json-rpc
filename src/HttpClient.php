<?php

namespace Elegant\JsonRpc;

use GuzzleHttp\Client as GuzzleHttpClient;

class HttpClient extends Client
{
    protected $httpClient;

    public function __construct($address, array $options = [])
    {
        $options['base_uri'] = $address;

        $this->httpClient = new GuzzleHttpClient($options);
    }

    public function request($method, array $params = null)
    {
        $headers = [];
        $headers['Content-Type'] = 'application/json';
        $headers['Connection-Type'] = 'close';

        $body = $this->encodeRequest($method, $params);

        $response = $this->httpClient->request('POST', '', compact('body', 'headers'));

        return $this->decodeResponse($response);
    }
}
