<?php

namespace Khien\Router;

class RouteTree
{
   public function __construct(
        private array $routes = [],
    ) {
    }

    public function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function findRoute(string $method, string $path): ?callable
    {
        return $this->routes[$method][$path] ?? null;
    }
}
