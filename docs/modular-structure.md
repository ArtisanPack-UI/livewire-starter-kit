---
title: Modular Structure
---

# Modular Laravel Structure

The Livewire Starter Kit offers an optional modular Laravel architecture that helps organize large applications into self-contained, reusable modules. This guide explains how to work with the modular structure.

## Overview

When you enable the modular structure during installation, the starter kit uses:
- **[nwidart/laravel-modules](https://nwidartmodules.com)** - Module management package
- **[mhmiton/laravel-modules-livewire](https://github.com/mhmiton/laravel-modules-livewire)** - Livewire integration for modules

## What is a Module?

A module is a self-contained package that includes:
- Controllers
- Models
- Views
- Migrations
- Routes
- Configuration
- Livewire components
- And more...

Each module is located in the `Modules/` directory at the root of your application.

## Default Modules

When you enable modular structure, three default modules are created:

### Admin Module
The Admin module is intended for administrative functionality like:
- Admin dashboard
- System settings
- User management (admin view)
- Application configuration

### Auth Module
The Auth module handles authentication and authorization:
- Login/logout
- Registration
- Password reset
- Email verification
- Two-factor authentication

### Users Module
The Users module manages user-related features:
- User profiles
- User settings
- User dashboard
- Account management

## Module Structure

Each module follows this structure:

```
Modules/
└── ModuleName/
    ├── Config/
    │   └── config.php
    ├── Console/
    ├── Database/
    │   ├── Migrations/
    │   ├── Seeders/
    │   └── Factories/
    ├── Entities/          # Models
    ├── Http/
    │   ├── Controllers/
    │   ├── Middleware/
    │   └── Requests/
    ├── Livewire/          # Livewire components
    ├── Providers/
    │   ├── ModuleNameServiceProvider.php
    │   └── RouteServiceProvider.php
    ├── Resources/
    │   ├── assets/
    │   └── views/
    ├── Routes/
    │   ├── api.php
    │   └── web.php
    ├── Tests/
    ├── composer.json
    └── module.json
```

## Creating Modules

### Create a New Module

```bash
php artisan module:make ModuleName
```

This creates a new module with the standard directory structure.

### Create Module Components

Create various components within a module:

**Controller:**
```bash
php artisan module:make-controller ControllerName ModuleName
```

**Model:**
```bash
php artisan module:make-model ModelName ModuleName
```

**Migration:**
```bash
php artisan module:make-migration create_table_name ModuleName
```

**Livewire Component:**
```bash
php artisan module:make-livewire ComponentName ModuleName
```

**Seeder:**
```bash
php artisan module:make-seed SeederName ModuleName
```

**Factory:**
```bash
php artisan module:make-factory FactoryName ModuleName
```

**Request:**
```bash
php artisan module:make-request RequestName ModuleName
```

## Working with Modules

### Enabling/Disabling Modules

**Enable a module:**
```bash
php artisan module:enable ModuleName
```

**Disable a module:**
```bash
php artisan module:disable ModuleName
```

**Check module status:**
```bash
php artisan module:list
```

### Running Migrations

**Run migrations for all modules:**
```bash
php artisan module:migrate
```

**Run migrations for a specific module:**
```bash
php artisan module:migrate ModuleName
```

**Rollback module migrations:**
```bash
php artisan module:migrate-rollback ModuleName
```

### Seeding Module Data

**Seed all modules:**
```bash
php artisan module:seed
```

**Seed a specific module:**
```bash
php artisan module:seed ModuleName
```

### Publishing Module Assets

**Publish assets for all modules:**
```bash
php artisan module:publish
```

**Publish assets for a specific module:**
```bash
php artisan module:publish ModuleName
```

## Module Routes

### Web Routes

Define web routes in `Modules/ModuleName/Routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleName\Http\Controllers\ModuleNameController;

Route::group(['middleware' => 'web'], function () {
    Route::get('/module-name', [ModuleNameController::class, 'index'])
        ->name('module-name.index');
});
```

### API Routes

Define API routes in `Modules/ModuleName/Routes/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleName\Http\Controllers\Api\ModuleNameController;

Route::group(['middleware' => 'api', 'prefix' => 'api/v1'], function () {
    Route::get('/module-name', [ModuleNameController::class, 'index']);
});
```

## Livewire Components in Modules

### Creating Livewire Components

Create a Livewire component within a module:

```bash
php artisan module:make-livewire ComponentName ModuleName
```

This creates:
- Component class: `Modules/ModuleName/Livewire/ComponentName.php`
- Component view: `Modules/ModuleName/Resources/views/livewire/component-name.blade.php`

### Using Module Livewire Components

Reference module Livewire components in your views:

```blade
<livewire:module-name::component-name />
```

Or using the directive:

```blade
@livewire('module-name::component-name')
```

## Module Configuration

### Module-Specific Configuration

Each module can have its own configuration in `Modules/ModuleName/Config/config.php`:

```php
<?php

return [
    'name' => 'ModuleName',
    'feature_flag' => env('MODULENAME_FEATURE', true),
    'settings' => [
        'option1' => 'value1',
        'option2' => 'value2',
    ],
];
```

Access module configuration:

```php
config('modulename.feature_flag');
config('modulename.settings.option1');
```

### Module Dependencies

Define module dependencies in `composer.json` within each module:

```json
{
    "name": "nwidart/modulename",
    "description": "",
    "type": "laravel-module",
    "require": {
        "php": "^8.2",
        "some/package": "^1.0"
    },
    "autoload": {
        "psr-4": {
            "Modules\\ModuleName\\": ""
        }
    }
}
```

## Best Practices

### Module Organization

1. **Single Responsibility**: Each module should focus on a specific domain or feature
2. **Loose Coupling**: Modules should be independent and minimally coupled
3. **Clear Boundaries**: Define clear interfaces between modules
4. **Consistent Naming**: Follow consistent naming conventions across modules

### Module Communication

**Via Events:**
```php
// In ModuleA
event(new \Modules\ModuleA\Events\SomethingHappened($data));

// In ModuleB
// Listen via EventServiceProvider
protected $listen = [
    \Modules\ModuleA\Events\SomethingHappened::class => [
        \Modules\ModuleB\Listeners\HandleSomething::class,
    ],
];
```

**Via Service Classes:**
```php
// Create a service in ModuleA
namespace Modules\ModuleA\Services;

class ModuleAService
{
    public function doSomething()
    {
        // Implementation
    }
}

// Use in ModuleB
$service = app(\Modules\ModuleA\Services\ModuleAService::class);
$service->doSomething();
```

### Testing Modules

**Run tests for a specific module:**
```bash
php artisan test Modules/ModuleName/Tests
```

**Run tests for all modules:**
```bash
php artisan test Modules/
```

## Converting Standard Application to Modular

If you didn't enable modular structure during installation but want to add it later:

1. Run the optional packages command:
```bash
php artisan artisanpack:optional-packages-command
```

2. Select the modular structure option

3. Move existing code to appropriate modules:
   - Move authentication code to the Auth module
   - Move user management to the Users module
   - Move admin features to the Admin module

4. Update namespaces and imports accordingly

5. Update route files to use module routes

6. Run `composer dump-autoload`

## Module Commands Reference

Here's a quick reference of commonly used module commands:

```bash
# Module Management
php artisan module:list                    # List all modules
php artisan module:make ModuleName         # Create new module
php artisan module:enable ModuleName       # Enable module
php artisan module:disable ModuleName      # Disable module
php artisan module:delete ModuleName       # Delete module

# Generators
php artisan module:make-command CommandName ModuleName
php artisan module:make-controller ControllerName ModuleName
php artisan module:make-model ModelName ModuleName
php artisan module:make-migration MigrationName ModuleName
php artisan module:make-livewire ComponentName ModuleName
php artisan module:make-factory FactoryName ModuleName
php artisan module:make-seeder SeederName ModuleName
php artisan module:make-request RequestName ModuleName
php artisan module:make-provider ProviderName ModuleName
php artisan module:make-middleware MiddlewareName ModuleName
php artisan module:make-mail MailName ModuleName
php artisan module:make-notification NotificationName ModuleName

# Database
php artisan module:migrate                 # Run migrations for all modules
php artisan module:migrate ModuleName      # Run migrations for specific module
php artisan module:migrate-rollback ModuleName
php artisan module:migrate-reset ModuleName
php artisan module:migrate-refresh ModuleName
php artisan module:seed                    # Seed all modules
php artisan module:seed ModuleName         # Seed specific module

# Publishing
php artisan module:publish                 # Publish all module assets
php artisan module:publish ModuleName      # Publish specific module assets
```

## Additional Resources

- [Laravel Modules Documentation](https://nwidartmodules.com/)
- [Laravel Modules Livewire Documentation](https://github.com/mhmiton/laravel-modules-livewire)
- [Module Development Best Practices](https://nwidartmodules.com/docs/v11/advanced-tools/artisan-commands)

## Next Steps

- Explore [Components](Components) for building module interfaces
- Review [Testing](Testing) for module testing strategies
- Check [Deployment](Deployment) for deploying modular applications
