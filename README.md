# Mini PHP Framework

A minimal PHP framework inspired by [Tempest](https://github.com/tempestphp/tempest-framework). Zero configuration, attribute-based routing, and automatic discovery.

## Philosophy

> A framework that gets out of your way.

- **Zero configuration** - sensible defaults, no boilerplate
- **Auto-discovery** - routes, handlers discovered automatically
- **Modern PHP** - attributes, readonly classes, type hints
- **Minimal dependencies** - only what's needed

## Installation

```bash
composer install
```

## Quick Start

Start the development server:

```bash
php -S localhost:8080 -t public
```

Visit `http://localhost:8080` - that's it!

## Directory Structure

```
miniphpframework/
├── app/                          # Your application code
│   ├── Controller/
│   │   └── TestController.php    # Example controller with attributes
│   └── HomeController.php        # Example controller
├── core/                         # Framework core
│   ├── Container/
│   │   └── Container.php         # Dependency injection container
│   ├── Core/
│   │   └── Kernel.php            # Application bootstrap
│   ├── Discovery/
│   │   ├── ClassReflector.php    # Reflection helper
│   │   ├── DiscoveryInterface.php
│   │   ├── DiscoveryItems.php
│   │   ├── DiscoveryLocation.php
│   │   ├── IsDiscovery.php       # Discovery trait
│   │   └── LoadDiscoveryClasses.php
│   ├── Http/
│   │   ├── Request.php           # PSR-7 request factory
│   │   └── Response.php          # HTTP response
│   └── Router/
│       ├── Attributes/
│       │   ├── Route.php         # Route interface
│       │   ├── Get.php
│       │   ├── Post.php
│       │   ├── Put.php
│       │   └── Delete.php
│       ├── HttpApplication.php   # Application entry
│       ├── RouteDiscovery.php    # Route discovery implementation
│       ├── Router.php            # Request dispatcher
│       └── RouteTree.php         # Route storage/matching
├── public/
│   └── index.php                 # Web entry point.
└── composer.json
```

## Routing

Define routes using PHP attributes on controller methods:

```php
<?php

namespace App;

use Khien\Router\Attributes\Get;
use Khien\Router\Attributes\Post;

class HomeController
{
    #[Get('/')]
    public function index()
    {
        return ['message' => 'Hello World!'];
    }

    #[Get('/users/{id}')]
    public function show(string $id)
    {
        return ['user_id' => $id];
    }

    #[Post('/users')]
    public function store()
    {
        return ['status' => 'created'];
    }
}
```

### Available Attributes

| Attribute | HTTP Method |
|-----------|-------------|
| `#[Get('/path')]` | GET |
| `#[Post('/path')]` | POST |
| `#[Put('/path')]` | PUT |
| `#[Delete('/path')]` | DELETE |

### Dynamic Parameters

Use `{param}` syntax for dynamic route segments:

```php
#[Get('/users/{id}')]
public function show(string $id)  // $id is injected automatically
{
    return ['user_id' => $id];
}

#[Get('/posts/{postId}/comments/{commentId}')]
public function showComment(string $postId, string $commentId)
{
    return ['post' => $postId, 'comment' => $commentId];
}
```

## Discovery System

The framework automatically discovers classes in the `app/` directory. Framework discoveries (like `RouteDiscovery`) are registered in `Kernel::registerFrameworkDiscoveries()`. App discoveries placed in `app/` are automatically registered.

To create a custom discovery:

```php
<?php

namespace App;

use Khien\Discovery\DiscoveryInterface;
use Khien\Discovery\DiscoveryLocation;
use Khien\Discovery\ClassReflector;
use Khien\Discovery\IsDiscovery;

class MyDiscovery implements DiscoveryInterface
{
    use IsDiscovery;

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        // Scan class for attributes, interfaces, etc.
        // Add items to $this->discoveryItems
    }

    public function apply(): void
    {
        // Process discovered items
        foreach ($this->discoveryItems as $item) {
            // Register handlers, routes, etc.
        }
    }
}
```

## Dependency Injection

The container automatically resolves constructor dependencies:

```php
class UserController
{
    public function __construct(
        private UserRepository $users,  // Auto-injected
    ) {}
}
```

Register singletons in `Kernel.php`:

```php
$this->container->singleton(MyService::class, new MyService());
```

## Response Types

Controllers can return:

- **Array** - automatically JSON encoded
- **String** - returned as-is
- **Response** - full control over status/headers

```php
// Array response (JSON)
return ['data' => $value];

// String response
return 'Hello World';

// Full Response object
$response = new Response();
$response->setStatus(201)
         ->setHeader('X-Custom', 'value')
         ->setBody(['created' => true]);
return $response;
```

## Request Flow

```
public/index.php
       │
       ▼
HttpApplication::boot($root)
       │
       ▼
Kernel::boot()
  ├── Load .env
  ├── Register singletons (Container, Kernel, RouteTree)
  ├── Register discovery locations (app/)
  ├── Register framework discoveries (RouteDiscovery)
  └── Run discovery (scan app/, find routes, etc.)
       │
       ▼
HttpApplication::run()
  ├── Capture PSR-7 request
  ├── Router::dispatch()
  │     ├── RouteTree::findRoute()
  │     ├── Resolve controller from container
  │     └── Call controller method with params
  └── Send response
```

## Environment Variables

Create a `.env` file in the project root:

```env
APP_ENV=development
APP_DEBUG=true
```

Access via `$_ENV` or `getenv()`.

## License

MIT
