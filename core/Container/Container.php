<?php

namespace Khien\Container;

use ArrayIterator;
use Closure;

class Container
{
    public function __construct(private ArrayIterator $singletons = new ArrayIterator())
    {
        // You can initialize any dependencies or services here if needed.
    }

    public function invoke($method, ...$params)
    {
        if (method_exists($method, '__invoke')) {
            $object = $this->resolve($method, ...$params);
            return $object->__invoke();
        }
    }

    public function singleton(string $className, mixed $definition): self
    {
        $this->singletons[$className] = $definition;

        return $this;
    }

    public function setSingletons(array $singletons): self
    {
        $this->singletons = new ArrayIterator($singletons);

        return $this;
    }

    public function getSingletons(?string $interface = null): array
    {
        $singletons = $this->singletons->getArrayCopy();

        if (is_null($interface)) {
            return $singletons;
        }

        return array_filter(
            array: $singletons,
            callback: static fn (mixed $_, string $key) => str_starts_with($key, "{$interface}#") || $key === $interface,
            mode: \ARRAY_FILTER_USE_BOTH,
        );
    }


    /**
     * @template TClassName of object
     * @param class-string<TClassName> $className
     * @return null|TClassName
     */
    public function get(string $className): mixed
    {
        // Check singletons first
        if (isset($this->singletons[$className])) {
            return $this->singletons[$className];
        }

        return $this->resolve($className);
    }

    private function resolve(string $className, ...$params): ?object
    {
        if (!class_exists($className)) {
            return null;
        }

        $reflection = new \ReflectionClass($className);

        if (!$reflection->isInstantiable()) {
            return null;
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        // Resolve constructor dependencies
        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                $dependencies[] = null;
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
