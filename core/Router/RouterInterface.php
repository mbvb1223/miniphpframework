<?php

namespace Khien\Router;

use Khien\Http\Response;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

interface RouterInterface
{
    public function dispatch(PsrRequest $request): Response;
}
