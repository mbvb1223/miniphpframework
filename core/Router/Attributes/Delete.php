<?php

namespace Khien\Router\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
final readonly class Delete implements Route
{
    public string $method;

    public function __construct(
        public string $uri,
    ) {
        $this->method = 'DELETE';
    }
}