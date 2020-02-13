<?php

namespace Elegant\Rpc;

use Exception;

abstract class Client
{
    const VERSION = '2.0';

    abstract public function request($method, array $params = null);

    public function encodeRequest($method, array $params = null)
    {
        $data = [];
        $data['jsonrpc'] = self::VERSION;
        $data['method'] = $method;
        if (isset($params)) $data['params'] = $params;
        $data['id'] = uniqid();

        return json_encode($data);
    }

    public function decodeResponse($raw)
    {
        $data = json_decode($raw, true);

        if ($data['jsonrpc'] !== self::VERSION)
            throw new Exception("Invalid Version.");
        if ($data['id'] !== $id)
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
