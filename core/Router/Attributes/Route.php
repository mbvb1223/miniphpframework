<?php

namespace Khien\Router\Attributes;

interface Route
{
    public string $method { get; }
    public string $uri { get; }
}