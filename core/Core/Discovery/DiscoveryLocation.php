<?php

namespace Khien\Core\Discovery;

class DiscoveryLocation
{
    public readonly string $namespace;
    public readonly string $path;

    public function __construct(
        string $namespace,
        string $path,
    ) {
        $this->namespace = $namespace;
        $this->path = realpath(rtrim($path, '\\/'));
    }
}
