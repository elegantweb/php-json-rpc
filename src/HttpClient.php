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

    public function request($method, array $params = null)
    {
        $headers = [];
        $headers['Content-Type'] = 'application/json';
        $headers['Connection-Type'] = 'close';

        $request = $this->createRequest($method, $params, uniqid());

        $body = $this->encodeRequest($request);

        $response = $this->httpClient->request('POST', '', compact('body', 'headers'))->getBody();

        return $this->decodeResponse($request, $response);
    }
}
