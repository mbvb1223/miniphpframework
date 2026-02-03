<?php

namespace App\Controller;

use Khien\Router\Attributes\Get;
use Khien\Router\Attributes\Post;

class TestController
{
    #[Get('/abc')]
    public function test()
    {
        return ['message' => 'Welcome to the Home Page!'];
    }
}
