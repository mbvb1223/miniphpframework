<?php

namespace App;

use Khien\Discovery\DiscoveryInterface;
use Khien\Discovery\DiscoveryItems;
use Khien\Discovery\DiscoveryLocation;
use Khien\Discovery\ClassReflector;
use Khien\Discovery\IsDiscovery;
use Khien\Router\Attributes\Route;
use Khien\Router\RouteTree;
use ReflectionAttribute;

class RouteDiscovery implements DiscoveryInterface
{
    use IsDiscovery;

    public function __construct(
        private RouteTree $routeTree,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        foreach ($class->getPublicMethods() as $method) {
            $routeAttributes = $method->getAttributes(
                Route::class,
                ReflectionAttribute::IS_INSTANCEOF
            );

            foreach ($routeAttributes as $routeAttribute) {
                $this->discoveryItems->add($location, [$method, $routeAttribute]);
            }
        }
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as [$method, $routeAttribute]) {
            $route = $routeAttribute->newInstance();
            $handler = [
                'class' => $method->getDeclaringClass()->getName(),
                'method' => $method->getName(),
            ];
            $this->routeTree->addRoute($route->method, $route->uri, $handler);
        }
    }
}