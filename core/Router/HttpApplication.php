<?php

namespace Khien\Router;

use Khien\Container\Container;
use Khien\Core\Khien;
use Khien\Http\RequestFactory;
use Khien\Http\ResponseSender;

class HttpApplication
{
    public function __construct(
        private readonly Container $container,
    ) {}

    public static function boot(string $root): self
    {
        $container = Khien::boot($root);
        $application = $container->get(HttpApplication::class);

        return $application;
    }

    public function run(): void
    {
        $router = $this->container->get(Router::class);
        $psrRequest = $this->container->get(RequestFactory::class)->moke();

        $responseSender = $this->container->get(ResponseSender::class);

        $responseSender->send($router->dispatch($psrRequest));

        $this->cleanup();
    }

    private function cleanup(): void
    {

    }
}
