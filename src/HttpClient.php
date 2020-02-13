<?php

namespace Elegant\JsonRpc;

use GuzzleHttp\Client as HttpClient;

class HttpClient extends Client
{
    protected $httpClient;

    public function __construct($address, array $options = [])
    {
        $this->httpClient = new HttpClient([
            'base_uri' => $address,
            ...$options
        ]);
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
