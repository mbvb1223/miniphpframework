<?php

namespace Khien\Core;

use Dotenv\Dotenv;
use Khien\Container\Container;

class Kernel
{
    public readonly Container $container;

    public function __construct(public string $root)
    {
        $this->container = $container ?? $this->createContainer();
    }

    public static function boot(
        ?string $root = null,
    ): Kernel
    {
        $kernal = new self($root);
        return $kernal->loadEnv()
            ->loadConfig()
            ->loadDiscovery();
    }

    private function loadEnv()
    {
        $dotenv = Dotenv::createUnsafeImmutable($this->root);
        $dotenv->safeLoad();

        return $this;
    }

    private function loadConfig()
    {
        return $this;
    }

    private function loadDiscovery()
    {
        return $this;
    }

    private function createContainer(): Container
    {
        return new Container();
    }
}
