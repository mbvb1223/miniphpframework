<?php

namespace Khien\Http;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

class RequestFactory
{
    public function moke(): PsrRequest
    {
        return new ServerRequest(
            method: $_SERVER['REQUEST_METHOD'] ?? 'GET',
            uri: $_SERVER['REQUEST_URI'] ?? '/',
            headers: getallheaders(),
            body: fopen('php://input', 'r'),
            serverParams: $_SERVER,
        );
    }
}
