<?php

namespace Khien\Core;

use Dotenv\Dotenv;
use Khien\Container\Container;
use Khien\Discovery\DiscoveryLocation;
use Khien\Discovery\LoadDiscoveryClasses;
use Khien\Discovery\LoadDiscoveryLocations;
use Khien\Router\RouteTree;

class Kernel implements KernelInterface
{
    public readonly Container $container;
    /** @var DiscoveryLocation[] */
    public array $discoveryLocations;
    public array $discoveryClasses;

    public function __construct(public string $root)
    {
        $this->container = $container ?? $this->createContainer();
    }

    public static function boot(
        ?string $root = null,
    ): Kernel
    {
        $kernal = new self($root);
        return $kernal
            ->loadEnv()
            ->registerKernel()
            ->loadConfig()
            ->loadDiscoveryLocations()
            ->loadDiscovery();
    }

    private function loadEnv()
    {
        $dotenv = Dotenv::createUnsafeImmutable($this->root);
        $dotenv->safeLoad();

        return $this;
    }

    private function registerKernel()
    {
        $this->container->singleton(KernelInterface::class, $this);
        $this->container->singleton(self::class, $this);
        $this->container->singleton(RouteTree::class, new RouteTree());

        return $this;
    }

    private function loadConfig()
    {
        return $this;
    }

    private function loadDiscoveryLocations()
    {
        $this->container->invoke(LoadDiscoveryLocations::class, $this);

        return $this;
    }

    private function loadDiscovery()
    {
        $this->container->invoke(LoadDiscoveryClasses::class);

        return $this;
    }

    private function createContainer(): Container
    {
        $container = new Container();
        $container->singleton(Container::class, $container);

        return $container;
    }
}
