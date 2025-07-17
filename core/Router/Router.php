<?php

namespace Khien\Router;

use Khien\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    public function dispatch(ServerRequestInterface $request): Response
    {
        $response = new Response();
        $response->setStatus(200)
                 ->setBody('Hello, World!');

        return $response;
    }
}
