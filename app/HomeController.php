<?php

namespace App;

use Khien\Router\Attributes\Get;
use Khien\Router\Attributes\Post;

class HomeController
{
    #[Get('/')]
    public function index()
    {
        return ['message' => 'Welcome to the Home Page!'];
    }

    #[Get('/test')]
    public function test()
    {
        return ['message' => 'Welcome to the Test Page!'];
    }

    #[Get('/users/{id}')]
    public function showUser(string $id)
    {
        return ['user_id' => $id];
    }

    #[Post('/users')]
    public function createUser()
    {
        return ['status' => 'created'];
    }
}
