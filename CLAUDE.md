# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Install dependencies
composer install

# Regenerate autoloader after adding/moving files
composer dump-autoload

# Start development server
php -S localhost:8080 -t public

# Test route discovery
php -r "require 'vendor/autoload.php'; print_r(Khien\Core\Kernel::boot(__DIR__)->container->get(Khien\Router\RouteTree::class)->getRoutes());"
```

## Architecture

This is a minimal PHP framework inspired by Tempest with zero-config, attribute-based routing, and auto-discovery.

### Namespaces
- `Khien\` → `core/` (framework code)
- `App\` → `app/` (user application code)

### Bootstrap Flow

```
public/index.php
    → HttpApplication::boot($root)
    → Kernel::boot()
        1. loadEnv()                    - Load .env via phpdotenv
        2. registerCore()               - Register Container, Kernel, RouteTree as singletons
        3. registerDiscoveryLocations() - Register app/ for scanning
        4. registerFrameworkDiscoveries() - Register RouteDiscovery
        5. runDiscovery()               - Scan app/ recursively, run discoveries, apply()
    → HttpApplication::run()
        → Router::dispatch() → RouteTree::findRoute() → Controller method → Response
```

### Key Components

**Container** (`core/Container/Container.php`): DI container with singleton registration and reflection-based auto-resolution of constructor dependencies.

**Discovery System** (`core/Discovery/`): Two-phase discovery - first collects all classes from registered locations (recursively), then runs registered `DiscoveryInterface` implementations on each class. Framework discoveries (like `RouteDiscovery`) are registered in `Kernel::registerFrameworkDiscoveries()`.

**Routing** (`core/Router/`): Attribute-based routing using `#[Get('/path')]`, `#[Post]`, etc. Routes are discovered via `RouteDiscovery` which scans for `Route` interface implementations on methods. `RouteTree` handles storage and matching including dynamic parameters (`{id}`).

### Adding New Discoveries

1. Create class implementing `DiscoveryInterface` with `IsDiscovery` trait
2. For framework discoveries: register in `Kernel::registerFrameworkDiscoveries()`
3. For app discoveries: place in `app/` - automatically registered

### Route Attributes

Controllers use PHP 8 attributes. The `Route` interface (`core/Router/Attributes/Route.php`) uses property hooks. Implementations: `Get`, `Post`, `Put`, `Delete`.

Dynamic route params (`/users/{id}`) are matched via regex and injected into controller method parameters by name.