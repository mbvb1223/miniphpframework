<?php

namespace Khien\Core\Discovery;

use Khien\Container\Container;
use Khien\Core\Kernel;

class LoadDiscoveryClasses
{
    public function __construct(
        private Kernel $kernel,
        private readonly Container $container,
    ) {
    }

    public function __invoke(): void
    {
        $discoveries = $this->build();

        foreach ($discoveries as $discovery) {
            $this->applyDiscovery($discovery);
        }
    }

    public function build()
    {
        foreach ($this->kernel->discoveryLocations as $location) {
            $namespace = $location->namespace;
            $path = $location->path;

            if (!is_dir($path)) {
                continue;
            }

            $files = glob($path . '/*.php');

            foreach ($files as $file) {
                if (is_file($file)) {
                    yield new DiscoveryClass($namespace, $file);
                }
            }
        }
    }



}
