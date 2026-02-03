<?php

namespace Khien\Discovery;

use IteratorAggregate;
use Traversable;
use ArrayIterator;

class DiscoveryItems implements IteratorAggregate
{
    public function __construct(
        private array $items = [],
    ) {}

    public function add(DiscoveryLocation $location, mixed $value): self
    {
        $this->items[$location->path] ??= [];
        $this->items[$location->path][] = $value;
        return $this;
    }

    public function getIterator(): Traversable
    {
        $allItems = [];
        foreach ($this->items as $locationItems) {
            foreach ($locationItems as $item) {
                $allItems[] = $item;
            }
        }
        return new ArrayIterator($allItems);
    }

    public function count(): int
    {
        $count = 0;
        foreach ($this->items as $locationItems) {
            $count += count($locationItems);
        }
        return $count;
    }
}