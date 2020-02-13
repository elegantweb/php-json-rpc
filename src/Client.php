<?php

namespace Elegant\JsonRpc;

use Exception;

abstract class Client
{
    const VERSION = '2.0';

    abstract public function request($method, array $params = null);

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
        $data = json_decode($response, true);

        if ($data['jsonrpc'] !== $request['jsonrpc'])
            throw new Exception("Invalid Version.");
        if ($data['id'] !== $request['id'])
            throw new Exception("Invalid ID, Expected: {$id}, Got: {$data['id']}.");
        elseif (isset($data['error']))
            throw new Exception($data['error']['message'], $data['error']['code']);
        elseif (isset($data['result']))
            return $data['result'];
        else
            throw new Exception("Invalid Response.");
    }

    public function __call($method, array $params)
    {
        return $this->request($method, $params);
    }
}
