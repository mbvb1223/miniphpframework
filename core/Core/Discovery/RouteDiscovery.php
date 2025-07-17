<?php

namespace Khien\Core\Discovery;

use Khien\Core\Kernel;

class RouteDiscovery
{
    public function discover(DiscoveryLocation $location, ClassReflector $class): void
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
            $route = DiscoveredRoute::fromRoute($routeAttribute, $method);
            $this->configurator->addRoute($route);
        }

        if ($this->configurator->isDirty()) {
            $this->routeConfig->apply($this->configurator->toRouteConfig());
        }
    }
}
