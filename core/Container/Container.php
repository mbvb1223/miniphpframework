<?php

namespace Khien\Container;

use ArrayIterator;

class Container
{
    public function __construct(private ArrayIterator $singletons = new ArrayIterator())
    {
        // You can initialize any dependencies or services here if needed.
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
        return $this->resolve($className);
    }

    private function resolve(string $className): ?object
    {
        if (class_exists($className)) {
            $reflection = new \ReflectionClass($className);
            if ($reflection->isInstantiable()) {
                return $reflection->newInstanceArgs([]);
            }
        }
        return null;
    }
}
