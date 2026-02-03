<?php

namespace Khien\Router;

class RouteTree
{
    public function __construct(
        private array $routes = [],
    ) {
    }

    public function addRoute(string $method, string $path, mixed $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Find a route matching the given method and path.
     * Returns array with 'handler' and 'params' or null if not found.
     */
    public function findRoute(string $method, string $path): ?array
    {
        $methodRoutes = $this->routes[$method] ?? [];

        // First try exact match
        if (isset($methodRoutes[$path])) {
            return [
                'handler' => $methodRoutes[$path],
                'params' => [],
            ];
        }

        // Try dynamic route matching
        foreach ($methodRoutes as $pattern => $handler) {
            $match = $this->matchDynamicRoute($pattern, $path);
            if ($match !== null) {
                return [
                    'handler' => $handler,
                    'params' => $match,
                ];
            }
        }

        return null;
    }

    /**
     * Match a dynamic route pattern against a path.
     * Returns extracted params or null if no match.
     */
    private function matchDynamicRoute(string $pattern, string $path): ?array
    {
        // Convert {param} to named capture groups
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $path, $matches)) {
            // Filter to only named matches (params)
            return array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
        }

        return null;
    }
}
