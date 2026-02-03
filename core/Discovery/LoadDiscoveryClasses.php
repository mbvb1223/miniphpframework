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
        private Container $container,
    ) {}

    public function __invoke(): void
    {
        // Use framework-registered discoveries
        $this->discoveries = $this->kernel->discoveries;

        // Scan all locations and run discoveries
        foreach ($this->kernel->discoveryLocations as $location) {
            foreach ($this->getClassesInLocation($location) as $classReflector) {
                // Allow app to register custom discoveries too
                $this->registerIfDiscovery($classReflector);
                $this->runDiscoveries($location, $classReflector);
            }
        }

        // Apply all discoveries
        foreach ($this->discoveries as $discovery) {
            $discovery->apply();
        }
    }

    /**
     * @return iterable<ClassReflector>
     */
    private function getClassesInLocation(DiscoveryLocation $location): iterable
    {
        if (!is_dir($location->path)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($location->path, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = substr($file->getPathname(), strlen($location->path) + 1);
                $className = $location->namespace . '\\' . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    $relativePath
                );

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
}