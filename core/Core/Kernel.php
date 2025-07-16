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
        return $kernal->loadEnv();
    }

    public function loadEnv()
    {
        $dotenv = Dotenv::createUnsafeImmutable($this->root);
        $dotenv->safeLoad();

        return $this;
    }

    public function createContainer(): Container
    {
        return new Container();
    }
}
