<?php

namespace Khien\Discovery;

use Khien\Container\Container;
use Khien\Core\Kernel;

class LoadDiscoveryClasses
{
    /** @var DiscoveryInterface[] */
    private array $discoveries = [];

    public function __construct(
        private Kernel $kernel,
        private readonly Container $container,
    ) {
    }

    public function __invoke(): void
    {
        // First pass: collect all classes and register discovery implementations
        $allClasses = [];
        foreach ($this->kernel->discoveryLocations as $location) {
            foreach ($this->getClassesInLocation($location) as $classReflector) {
                $allClasses[] = [$location, $classReflector];
                $this->registerIfDiscovery($classReflector);
            }
        }

        // Second pass: run discoveries on all classes
        foreach ($allClasses as [$location, $classReflector]) {
            $this->runDiscoveries($location, $classReflector);
        }

        $this->applyAllDiscoveries();
    }

    /**
     * @return iterable<ClassReflector>
     */
    private function getClassesInLocation(DiscoveryLocation $location): iterable
    {
        if (!is_dir($location->path)) {
            return;
        }

        $files = glob($location->path . '/*.php');

        foreach ($files as $file) {
            if (is_file($file)) {
                $className = $location->namespace . '\\' . basename($file, '.php');

                if (class_exists($className)) {
                    yield new ClassReflector($className);
                }
            }
        }
    }

    private function registerIfDiscovery(ClassReflector $classReflector): void
    {
        if ($classReflector->implementsInterface(DiscoveryInterface::class)) {
            $discovery = $this->container->get($classReflector->getName());
            $discovery->setItems(new DiscoveryItems());
            $this->discoveries[] = $discovery;
        }
    }

    private function runDiscoveries(DiscoveryLocation $location, ClassReflector $classReflector): void
    {
        foreach ($this->discoveries as $discovery) {
            $discovery->discover($location, $classReflector);
        }
    }

    private function applyAllDiscoveries(): void
    {
        foreach ($this->discoveries as $discovery) {
            $discovery->apply();
        }
    }
}
