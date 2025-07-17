<?php

namespace Khien\Http;

class ResponseSender
{
    public function send(Response $response): Response
    {
        ob_start();

        $this->sendContent($response);

        ob_end_flush();

        return $response;
    }

    public function sendContent(Response $response)
    {
        $body = $response->body;
        if (is_array($body)) {
            echo json_encode($body);
        } else {
            echo $body;
        }

        ob_flush();
    }
}
