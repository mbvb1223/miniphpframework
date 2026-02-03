<?php

namespace Khien\Core;

use Dotenv\Dotenv;
use Khien\Container\Container;
use Khien\Discovery\DiscoveryLocation;
use Khien\Discovery\LoadDiscoveryClasses;
use Khien\Router\RouteTree;

class Kernel
{
    public readonly Container $container;

    /** @var DiscoveryLocation[] */
    public array $discoveryLocations = [];

    public function __construct(
        public readonly string $root,
    ) {
        $this->container = new Container();
    }

    public static function boot(string $root): self
    {
        $kernel = new self($root);

        return $kernel
            ->loadEnv()
            ->registerCore()
            ->registerDiscoveryLocations()
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

    private function runDiscovery(): self
    {
        $this->container->call(LoadDiscoveryClasses::class);

        return $this;
    }
}