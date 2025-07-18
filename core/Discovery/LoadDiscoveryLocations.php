<?php

namespace Khien\Discovery;

use Khien\Core\Kernel;

class LoadDiscoveryLocations
{
    public function __construct(
        private readonly Kernel $kernel,
    ) {
    }

    public function __invoke(): void
    {
        $this->kernel->discoveryLocations = [...$this->discoverAppNamespaces()];
    }

    public function discoverAppNamespaces(): array
    {
        $discoveredLocations[] = new DiscoveryLocation(
            'App',
            $this->kernel->root . '/app'
        );

        return $discoveredLocations;
    }

}
