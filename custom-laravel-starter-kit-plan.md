# Custom Laravel Starter Kit Plan

## Overview

This document outlines the plan for creating a custom Laravel Starter Kit based on the default Laravel starter kit but with ArtisanPack UI components instead of FluxUI components. The custom starter kit will provide a streamlined setup process with interactive prompts for optional packages and UI customization.

## Required and Optional Packages

### Required Packages
- `artisanpack-ui/accessibility`
- `artisanpack-ui/security`
- `artisanpack-ui/livewire-ui-components` (replacing FluxUI components)

### Optional Packages (with interactive prompts)
- `artisanpack-ui/code-style`
- `artisanpack-ui/icons`
- `artisanpack-ui/cms-framework`

## Implementation Plan

### 1. Project Structure Setup

Create a new Laravel starter kit project with the following structure:

```
livewire-starter-kit/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
│   ├── views/
│   │   ├── components/
│   │   ├── livewire/
│   │   │   ├── auth/
│   │   │   ├── settings/
│   │   │   └── ...
│   │   └── ...
├── routes/
├── scripts/
│   └── install.php
├── storage/
├── tests/
├── composer.json
└── package.json
```

### 2. Composer Configuration

Modify the `composer.json` file to:
- Replace `livewire/flux` with `artisanpack-ui/livewire-ui-components`
- Add required packages: `artisanpack-ui/accessibility`, `artisanpack-ui/security`
- Configure post-install scripts to run the interactive installation script

```json
{
    "name": "artisanpack-ui/livewire-starter-kit",
    "type": "project",
    "description": "ArtisanPack UI Laravel starter kit for Livewire.",
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "laravel/tinker": "^2.10.1",
        "livewire/volt": "^1.7.0",
        "artisanpack-ui/livewire-ui-components": "^1.0",
        "artisanpack-ui/accessibility": "^1.0",
        "artisanpack-ui/security": "^1.0"
    },
    "scripts": {
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php scripts/install.php",
            "@php artisan migrate --graceful --ansi"
        ]
    }
}
```

### 3. Interactive Installation Script

Create a `scripts/install.php` file that will:

1. Bootstrap Laravel application
2. Implement a `runCommand()` helper function
3. Prompt for optional packages
4. Prompt for UI colors
5. Handle special case for CMS framework
6. Finalize installation

```php
<?php

// Bootstrap Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Process;
use Laravel\Prompts\Prompts;

// Helper function to run shell commands
function runCommand($command, $message = null) {
    if ($message) {
        echo $message . PHP_EOL;
    }

    $process = Process::run($command);

    if ($process->successful()) {
        echo $process->output() . PHP_EOL;
        return true;
    } else {
        echo "Error: " . $process->errorOutput() . PHP_EOL;
        return false;
    }
}

echo "🚀 Setting up your ArtisanPack UI Laravel Starter Kit..." . PHP_EOL;

// Prompt for optional packages
$installCodeStyle = Prompts::confirm(
    label: 'Would you like to install the artisanpack-ui/code-style package?',
    default: false
);

$installIcons = Prompts::confirm(
    label: 'Would you like to install the artisanpack-ui/icons package?',
    default: false
);

$installCmsFramework = Prompts::confirm(
    label: 'Would you like to install the artisanpack-ui/cms-framework package?',
    default: false
);

// Install optional packages if selected
if ($installCodeStyle) {
    runCommand('composer require artisanpack-ui/code-style --no-interaction', 'Installing Code Style package...');
}

if ($installIcons) {
    runCommand('composer require artisanpack-ui/icons --no-interaction', 'Installing Icons package...');
}

if ($installCmsFramework) {
    runCommand('composer require artisanpack-ui/cms-framework --no-interaction', 'Installing CMS Framework package...');

    // Remove User model and migrations as CMS Framework provides its own
    echo "Removing default User model and migrations as CMS Framework provides its own..." . PHP_EOL;
    if (file_exists(__DIR__ . '/../app/Models/User.php')) {
        unlink(__DIR__ . '/../app/Models/User.php');
    }
    
    // Find and remove the create_users_table migration
    $migrations = glob(__DIR__ . '/../database/migrations/*_create_users_table.php');
    foreach ($migrations as $migration) {
        unlink($migration);
    }
    
    // Update auth views to work with CMS Framework's User model
    echo "Updating auth views to work with CMS Framework's User model..." . PHP_EOL;
    copy(__DIR__ . '/../stubs/cms-compatible/register.blade.php', __DIR__ . '/../resources/views/livewire/auth/register.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/login.blade.php', __DIR__ . '/../resources/views/livewire/auth/login.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/forgot-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/forgot-password.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/reset-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/reset-password.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/profile.blade.php', __DIR__ . '/../resources/views/livewire/settings/profile.blade.php');
}

// Prompt for UI colors
echo "Let's set up your UI colors..." . PHP_EOL;

$primaryColor = Prompts::text(
    label: 'Enter your primary color (hex code)',
    default: '#4f46e5',
    validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code (e.g. #4f46e5)' : null
);

$secondaryColor = Prompts::text(
    label: 'Enter your secondary color (hex code)',
    default: '#6b7280',
    validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code (e.g. #6b7280)' : null
);

$accentColor = Prompts::text(
    label: 'Enter your accent color (hex code)',
    default: '#10b981',
    validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code (e.g. #10b981)' : null
);

// Generate CSS with the selected colors
$cssCommand = "php artisan generate:css --primary={$primaryColor} --secondary={$secondaryColor} --accent={$accentColor}";
runCommand($cssCommand, 'Generating custom CSS with your color choices...');

// Finalize installation
echo "🎉 Your ArtisanPack UI Laravel Starter Kit is almost ready!" . PHP_EOL;

// Clean up installation script
$removeScript = Prompts::confirm(
    label: 'Would you like to remove the installation script?',
    default: true
);

if ($removeScript) {
    unlink(__FILE__);
    echo "Installation script removed." . PHP_EOL;
}

echo "✅ Setup complete! Run 'php artisan serve' to start your application." . PHP_EOL;
```

### 4. View Modifications

#### 4.1 Replace FluxUI Components

Replace all FluxUI components with ArtisanPack UI components throughout the application:

- `flux:input` → `<x-artisanpack-input>`
- `flux:button` → `<x-artisanpack-button>`
- `flux:link` → `<x-artisanpack-link>`
- `flux:text` → `<x-artisanpack-text>`
- etc.

Note: All ArtisanPack UI components are called using the format `<x-artisanpack-{component name}>` rather than the prefix format used by FluxUI.

#### 4.2 CMS Framework Integration

If the CMS Framework package is installed:

1. **Remove User Model** (`app/Models/User.php`):
   - The CMS Framework package includes its own User model
   - The default User model should be removed to avoid conflicts

2. **Remove User Migration** (`database/migrations/create_users_table.php`):
   - The CMS Framework package includes its own migration for the users table
   - The default migration should be removed to avoid conflicts

3. **Keep Auth Views**:
   - The CMS Framework package does NOT include any views
   - Keep all auth views from the default Laravel and Livewire Starter Kit
   - Update the views to work with the CMS Framework's User model

### 5. Conditional File Structure

Create a mechanism to conditionally modify files based on the packages selected during installation:

```php
// In scripts/install.php

if ($installCmsFramework) {
    // Remove default User model and migrations as CMS Framework provides its own
    if (file_exists(__DIR__ . '/../app/Models/User.php')) {
        unlink(__DIR__ . '/../app/Models/User.php');
        echo "Removed default User model as CMS Framework provides its own." . PHP_EOL;
    }
    
    // Find and remove the create_users_table migration
    $migrations = glob(__DIR__ . '/../database/migrations/*_create_users_table.php');
    foreach ($migrations as $migration) {
        unlink($migration);
        echo "Removed default users table migration as CMS Framework provides its own." . PHP_EOL;
    }
    
    // Update auth views to work with CMS Framework's User model
    echo "Updating auth views to work with CMS Framework's User model..." . PHP_EOL;
    copy(__DIR__ . '/../stubs/cms-compatible/register.blade.php', __DIR__ . '/../resources/views/livewire/auth/register.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/login.blade.php', __DIR__ . '/../resources/views/livewire/auth/login.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/forgot-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/forgot-password.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/reset-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/reset-password.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/profile.blade.php', __DIR__ . '/../resources/views/livewire/settings/profile.blade.php');
} else {
    // Copy standard versions of files
    copy(__DIR__ . '/../stubs/standard/User.php', __DIR__ . '/../app/Models/User.php');
    copy(__DIR__ . '/../stubs/standard/create_users_table.php', __DIR__ . '/../database/migrations/xxxx_xx_xx_000000_create_users_table.php');
    copy(__DIR__ . '/../stubs/standard/register.blade.php', __DIR__ . '/../resources/views/livewire/auth/register.blade.php');
    copy(__DIR__ . '/../stubs/standard/login.blade.php', __DIR__ . '/../resources/views/livewire/auth/login.blade.php');
    copy(__DIR__ . '/../stubs/standard/forgot-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/forgot-password.blade.php');
    copy(__DIR__ . '/../stubs/standard/reset-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/reset-password.blade.php');
    copy(__DIR__ . '/../stubs/standard/profile.blade.php', __DIR__ . '/../resources/views/livewire/settings/profile.blade.php');
}
```

### 6. Stub Files

Create stub files for both standard and CMS-compatible versions of key files:

#### 6.1 Standard Files

##### 6.1.1 Standard User Model

```php
// stubs/standard/User.php
namespace App\Models;

// Standard User model with 'name' field
```

##### 6.1.2 Standard Auth Views

```php
// stubs/standard/register.blade.php
// Registration view with single 'name' field

// stubs/standard/login.blade.php
// Login view with email and password fields

// stubs/standard/forgot-password.blade.php
// Forgot password view with email field

// stubs/standard/reset-password.blade.php
// Reset password view with email, password, and password confirmation fields

// stubs/standard/profile.blade.php
// Profile settings view with single 'name' field
```

#### 6.2 CMS-Compatible Files

##### 6.2.1 CMS-Compatible Auth Views

```php
// stubs/cms-compatible/register.blade.php
// Registration view compatible with CMS Framework's User model (username, first_name, last_name fields)

// stubs/cms-compatible/login.blade.php
// Login view compatible with CMS Framework's User model (username/email and password fields)

// stubs/cms-compatible/forgot-password.blade.php
// Forgot password view compatible with CMS Framework's User model

// stubs/cms-compatible/reset-password.blade.php
// Reset password view compatible with CMS Framework's User model

// stubs/cms-compatible/profile.blade.php
// Profile settings view compatible with CMS Framework's User model (username, first_name, last_name fields)
```

Note: We don't need to create CMS-specific User model and migration stubs as the CMS Framework package provides its own. However, we do need to create CMS-compatible auth view stubs since the CMS Framework package does not include any views.

### 7. Testing and Validation

1. **Unit Tests**:
   - Test the standard User model
   - Test authentication flows with standard configuration
   - Test authentication flows with CMS Framework (using its User model)
   - Test profile updates with both standard and CMS-compatible views

2. **Integration Tests**:
   - Test the installation script
   - Test the CSS generation
   - Test the file removal when CMS Framework is installed
   - Test the conditional file structure
   - Test that auth views are properly updated for CMS Framework compatibility

3. **Manual Testing**:
   - Test the full installation process with and without CMS Framework
   - Verify all UI components render correctly
   - Test authentication and user management with standard configuration
   - Test authentication and user management with CMS Framework
   - Verify that auth views work correctly with CMS Framework's User model

## Implementation Timeline

1. **Week 1**: Set up basic project structure and modify composer.json
2. **Week 2**: Create installation script and implement package prompts
3. **Week 3**: Replace FluxUI components with ArtisanPack UI components
4. **Week 4**: Implement CMS framework integration and conditional file structure
5. **Week 5**: Testing, bug fixes, and documentation

## Conclusion

This plan outlines the steps to create a custom Laravel Starter Kit that replaces FluxUI components with ArtisanPack UI components, adds required packages, and provides interactive prompts for optional packages and UI customization. The plan also addresses the special case of the CMS Framework package, which includes its own User model and migration files but does not include any views. 

When the CMS Framework package is installed, the plan:
1. Removes the default User model and migration files to avoid conflicts
2. Keeps all auth views from the default Laravel and Livewire Starter Kit
3. Updates these views to be compatible with the CMS Framework's User model

By following this plan, we can create a robust and flexible starter kit that meets the specified requirements while maintaining compatibility with Laravel's core functionality and properly integrating with the CMS Framework package when selected.
