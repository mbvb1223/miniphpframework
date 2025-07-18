<?php

namespace Khien\Discovery;


trait IsDiscovery
{
    private DiscoveryItems $discoveryItems;

    public function getItems(): DiscoveryItems
    {
        return $this->discoveryItems;
    }

    public function setItems(DiscoveryItems $items): void
    {
        $this->discoveryItems = $items;
    }
}
