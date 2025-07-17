<?php

namespace Khien\Core\Discovery;

class DiscoveryItems
{
    public function __construct(
        private array $items = [],
    ) {}

    public function addForLocation(DiscoveryLocation $location, array $values): self
    {
        $existingValues = $this->items[$location->path] ?? [];

        $this->items[$location->path] = [...$existingValues, ...$values];

        return $this;
    }

    public function add(DiscoveryLocation $location, mixed $value): self
    {
        $this->items[$location->path] ??= [];
        $this->items[$location->path][] = $value;

        return $this;
    }
}
