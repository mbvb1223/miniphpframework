<?php

namespace Khien\Http;

class Response
{
    public int $status;
    public mixed $body;

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setBody(mixed $body): self
    {
        $this->body = $body;
        return $this;
    }
}
