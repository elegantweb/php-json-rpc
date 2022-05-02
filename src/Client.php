<?php

namespace Elegant\JsonRpc;

use UnexpectedValueException;

abstract class Client
{
    const VERSION = '2.0';

    abstract public function sendRequest($body);

    public function request($method, array $params = null)
    {
        $request = $this->createRequest($method, $params, uniqid());

        $body = $this->encodeRequest($request);

        $response = $this->sendRequest($body);

        return $this->decodeResponse($request, $response);
    }

    public function createRequest($method, array $params = null, $id = null)
    {
        $data = [];
        $data['jsonrpc'] = self::VERSION;
        $data['method'] = $method;
        if (isset($params)) $data['params'] = $params;
        if (isset($id)) $data['id'] = $id;
        return $data;
    }

    public function encodeRequest(array $request)
    {
        return json_encode($request);
    }

    public function decodeResponse(array $request, $response)
    {
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if (isset($data['jsonrpc']) and $data['jsonrpc'] !== $request['jsonrpc'])
            throw new UnexpectedValueException("Invalid version.");
        if (isset($data['id']) and $data['id'] !== $request['id'])
            throw new UnexpectedValueException("Invalid ID, Expected: {$request['id']}, Got: {$data['id']}.");
        elseif (isset($data['error']))
            throw new UnexpectedValueException($data['error']['message'], $data['error']['code']);
        elseif (isset($data['result']))
            return $data['result'];
        else
            throw new UnexpectedValueException("Invalid Response.");
    }

    public function __call($method, array $params)
    {
        return $this->request($method, $params);
    }
}
