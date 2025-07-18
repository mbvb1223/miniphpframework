<?php

namespace Khien\Core\Discovery;

use Khien\Core\Kernel;
use Khien\Router\Attributes\Route;
use Khien\Router\RouteTree;

class RouteDiscovery implements DiscoveryInterface
{
    use IsDiscovery;

    public function __construct(Kernel $kernel, private RouteTree $routeTree)
    {

    }
    public function discover(DiscoveryLocation $location, $class): void
    {
        foreach ($class->getPublicMethods() as $method) {
            $routeAttributes = $method->getAttributes(Route::class);

            foreach ($routeAttributes as $routeAttribute) {
                $this->discoveryItems->add($location, [$method, $routeAttribute]);
            }
        }
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as [$method, $routeAttribute]) {
            $this->routeTree->addRoute($method);
        }
    }
}
