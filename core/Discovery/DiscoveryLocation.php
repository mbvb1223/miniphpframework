<?php

namespace Khien\Discovery;

readonly class DiscoveryLocation
{
    public function __construct(
        public string $namespace,
        public string $path,
    ) {}
}