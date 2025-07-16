<?php

namespace Khien\Core;

use Khien\Container\Container;

class Khien
{
    public static function boot(
        ?string $root = null,
    ): Container {
        $root ??= getcwd();

        // Kernel
        return Kernel::boot($root)->container;
    }
}
