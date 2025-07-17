<?php

namespace Khien\Core\Discovery;

use Khien\Core\Kernel;

class LoadDiscoveryLocations
{
    public function __construct(
        private Kernel $kernel,
    ) {
    }

    public function __invoke(): void
    {
        $this->kernel->discoveryLocations = [...$this->discoverAppNamespaces()];
    }

    public function discoverAppNamespaces(): array
    {
        $discoveredLocations[] = new DiscoveryLocation('Khien',
            $this->kernel->root . '/app'
        );
        return [];
    }

}
