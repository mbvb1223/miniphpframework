<?php

namespace Khien\Router;

use http\Client\Response;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

interface RouterInterface
{
    public function dispatch(PsrRequest $request): Response;
}
