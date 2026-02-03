<?php

namespace Khien\Container;

use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private array $singletons = [];

    public function singleton(string $id, mixed $concrete): self
    {
        $this->singletons[$id] = $concrete;
        return $this;
    }

    public function get(string $id): mixed
    {
        if (isset($this->singletons[$id])) {
            return $this->singletons[$id];
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->singletons[$id]);
    }

    private function resolve(string $className): ?object
    {
        if (!class_exists($className)) {
            return null;
        }

        $reflection = new ReflectionClass($className);

        if (!$reflection->isInstantiable()) {
            return null;
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                $dependencies[] = null;
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    public function call(string $className): mixed
    {
        $instance = $this->get($className);

        if ($instance && method_exists($instance, '__invoke')) {
            return $instance();
        }

        return null;
    }
}