<?php

namespace Khien\Http;

use GuzzleHttp\Psr7\Response;

class ResponseSender
{
    public function send(Response $response): Response
    {
        return $response;
    }
}
