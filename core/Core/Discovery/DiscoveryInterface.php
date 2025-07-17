<?php

namespace Khien\Core\Discovery;

use Khien\Core\Kernel;

interface DiscoveryInterface
{
    public function discover(DiscoveryLocation $location, ClassReflector $class): void;

    public function getItems(): DiscoveryItems;

    public function setItems(DiscoveryItems $items): void;

    public function apply(): void;
}
