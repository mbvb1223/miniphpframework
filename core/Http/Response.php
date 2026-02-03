<?php

namespace Khien\Http;

class Response
{
    private int $status = 200;
    private mixed $body = '';
    private array $headers = [];

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

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getBody(): mixed
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        if (is_array($this->body)) {
            header('Content-Type: application/json');
            echo json_encode($this->body);
        } else {
            echo $this->body;
        }
    }
}