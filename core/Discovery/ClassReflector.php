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

    public function getReflection(): ReflectionClass
    {
        return $this->reflection;
    }

    /**
     * @return ReflectionMethod[]
     */
    public function getPublicMethods(): array
    {
        return $this->reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    }

    public function getName(): string
    {
        return $this->reflection->getName();
    }

    public function getShortName(): string
    {
        return $this->reflection->getShortName();
    }

    public function implementsInterface(string $interface): bool
    {
        return $this->reflection->implementsInterface($interface);
    }

    public function hasAttribute(string $attributeClass): bool
    {
        return count($this->reflection->getAttributes($attributeClass)) > 0;
    }

    public function getAttributes(string $attributeClass): array
    {
        return $this->reflection->getAttributes($attributeClass);
    }
}