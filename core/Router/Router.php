<?php

namespace Khien\Router;

class Router implements RouterInterface
{
    public function dispatch(\Psr\Http\Message\ServerRequestInterface $request): \http\Client\Response
    {
        // Here you would implement the logic to handle the request and return a response.
        // This is just a placeholder implementation.
        $response = new \http\Client\Response();
        $response->setBody('Hello, world!');

        return $response;
    }
}
