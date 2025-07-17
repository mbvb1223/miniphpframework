<?php

namespace App;

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
}
