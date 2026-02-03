<?php

namespace Khien\Core;

use Dotenv\Dotenv;
use Khien\Container\Container;
use Khien\Discovery\DiscoveryInterface;
use Khien\Discovery\DiscoveryItems;
use Khien\Discovery\DiscoveryLocation;
use Khien\Discovery\LoadDiscoveryClasses;
use Khien\Router\RouteDiscovery;
use Khien\Router\RouteTree;

class Kernel
{
    public readonly Container $container;

    /** @var DiscoveryLocation[] */
    public array $discoveryLocations = [];

    /** @var DiscoveryInterface[] */
    public array $discoveries = [];

    public readonly string $root;

    public function __construct(string $root)
    {
        $this->root = rtrim($root, '/');
        $this->container = new Container();
    }

    public static function boot(string $root): self
    {
        $kernel = new self($root);

        return $kernel
            ->loadEnv()
            ->registerCore()
            ->registerDiscoveryLocations()
            ->registerFrameworkDiscoveries()
            ->runDiscovery();
    }

    private function loadEnv(): self
    {
        $dotenv = Dotenv::createUnsafeImmutable($this->root);
        $dotenv->safeLoad();

        return $this;
    }

    private function registerCore(): self
    {
        $this->container->singleton(Container::class, $this->container);
        $this->container->singleton(self::class, $this);
        $this->container->singleton(RouteTree::class, new RouteTree());

        return $this;
    }

    private function registerDiscoveryLocations(): self
    {
        $this->discoveryLocations[] = new DiscoveryLocation(
            'App',
            $this->root . '/app'
        );

        return $this;
    }

    private function registerFrameworkDiscoveries(): self
    {
        $routeDiscovery = $this->container->get(RouteDiscovery::class);
        $routeDiscovery->setItems(new DiscoveryItems());
        $this->discoveries[] = $routeDiscovery;

        return $this;
    }

    private function runDiscovery(): self
    {
        $this->container->call(LoadDiscoveryClasses::class);

        return $this;
    }
}