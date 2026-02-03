<?php

namespace Khien\Router;

use Khien\Container\Container;
use Khien\Http\Response;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionMethod;

class Router implements RouterInterface
{
    public function __construct(
        private RouteTree $routeTree,
        private Container $container,
    ) {
    }

    public function dispatch(ServerRequestInterface $request): Response
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        $match = $this->routeTree->findRoute($method, $path);

        if ($match === null) {
            return $this->notFound();
        }

        return $this->callHandler($match['handler'], $match['params']);
    }

    private function callHandler(array $handler, array $params): Response
    {
        $controller = $this->container->get($handler['class']);
        $methodName = $handler['method'];

        $reflection = new ReflectionMethod($controller, $methodName);
        $args = $this->resolveMethodParams($reflection, $params);

        $result = $reflection->invokeArgs($controller, $args);

        if ($result instanceof Response) {
            return $result;
        }

        $response = new Response();
        $response->setStatus(200)->setBody($result);

        return $response;
    }

    private function resolveMethodParams(ReflectionMethod $method, array $routeParams): array
    {
        $args = [];

        foreach ($method->getParameters() as $parameter) {
            $name = $parameter->getName();

            if (isset($routeParams[$name])) {
                $args[] = $routeParams[$name];
            } elseif ($parameter->isDefaultValueAvailable()) {
                $args[] = $parameter->getDefaultValue();
            } else {
                $args[] = null;
            }
        }

        return $args;
    }

    private function notFound(): Response
    {
        $response = new Response();
        $response->setStatus(404)->setBody('Not Found');

        return $response;
    }
}
