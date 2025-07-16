<?php
declare(strict_types=1);

use Khien\Router\HttpApplication;

require_once __DIR__ . '/../vendor/autoload.php';

HttpApplication::boot(__DIR__ . '/../')->run();

exit();
