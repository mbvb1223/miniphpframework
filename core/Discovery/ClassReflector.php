<?php

namespace Khien\Discovery;

use ReflectionClass;
use ReflectionMethod;

class ClassReflector
{
    private ReflectionClass $reflection;

    public function __construct(
        public readonly string $className,
    ) {
        $this->reflection = new ReflectionClass($className);
    }

    public function getName(): string
    {
        return $this->reflection->getName();
    }

    /**
     * @return ReflectionMethod[]
     */
    public function getPublicMethods(): array
    {
        return $this->reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    }

    public function implementsInterface(string $interface): bool
    {
        return $this->reflection->implementsInterface($interface);
    }

    public function getAttributes(string $attributeClass): array
    {
        return $this->reflection->getAttributes($attributeClass);
    }
}
