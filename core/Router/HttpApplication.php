<?php

namespace Khien\Router;

use Khien\Container\Container;
use Khien\Core\Kernel;
use Khien\Http\Request;

readonly class HttpApplication
{
    public function __construct(
        private Container $container,
    ) {}

    public static function boot(string $root): self
    {
        $kernel = Kernel::boot($root);

        return $kernel->container->get(self::class);
    }

    public function run(): void
    {
        $router = $this->container->get(Router::class);
        $request = Request::capture();
        $response = $router->dispatch($request);

        $response->send();
    }
}