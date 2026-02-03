<?php

namespace Khien\Http;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class Request
{
    public static function capture(): ServerRequestInterface
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $headers = getallheaders() ?: [];
        $body = file_get_contents('php://input') ?: null;

        return new ServerRequest($method, $uri, $headers, $body, '1.1', $_SERVER);
    }
}