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

    // Update User model and migrations for CMS Framework
    echo "Updating User model and migrations for CMS Framework..." . PHP_EOL;
    // This will be handled in a separate step
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

If the CMS Framework package is installed, modify the following files:

1. **User Model** (`app/Models/User.php`):
   - Add `username`, `first_name`, and `last_name` fields
   - Update fillable properties
   - Add accessor for full name

2. **User Migration** (`database/migrations/create_users_table.php`):
   - Add columns for `username`, `first_name`, and `last_name`
   - Remove the generic `name` column

3. **Registration View** (`resources/views/livewire/auth/register.blade.php`):
   - Replace the single name field with separate fields for username, first name, and last name
   - Update validation rules

4. **Profile Settings View** (`resources/views/livewire/settings/profile.blade.php`):
   - Replace the single name field with separate fields for username, first name, and last name
   - Update validation rules and form submission logic

### 5. Conditional File Structure

Create a mechanism to conditionally modify files based on the packages selected during installation:

```php
// In scripts/install.php

if ($installCmsFramework) {
    // Copy CMS-specific versions of files
    copy(__DIR__ . '/../stubs/cms/User.php', __DIR__ . '/../app/Models/User.php');
    copy(__DIR__ . '/../stubs/cms/create_users_table.php', __DIR__ . '/../database/migrations/xxxx_xx_xx_000000_create_users_table.php');
    copy(__DIR__ . '/../stubs/cms/register.blade.php', __DIR__ . '/../resources/views/livewire/auth/register.blade.php');
    copy(__DIR__ . '/../stubs/cms/profile.blade.php', __DIR__ . '/../resources/views/livewire/settings/profile.blade.php');
} else {
    // Copy standard versions of files
    copy(__DIR__ . '/../stubs/standard/User.php', __DIR__ . '/../app/Models/User.php');
    copy(__DIR__ . '/../stubs/standard/create_users_table.php', __DIR__ . '/../database/migrations/xxxx_xx_xx_000000_create_users_table.php');
    copy(__DIR__ . '/../stubs/standard/register.blade.php', __DIR__ . '/../resources/views/livewire/auth/register.blade.php');
    copy(__DIR__ . '/../stubs/standard/profile.blade.php', __DIR__ . '/../resources/views/livewire/settings/profile.blade.php');
}
```

### 6. Stub Files

Create stub files for both standard and CMS versions of key files:

#### 6.1 Standard User Model

```php
// stubs/standard/User.php
namespace App\Models;

// Standard User model with 'name' field
```

#### 6.2 CMS User Model

```php
// stubs/cms/User.php
namespace App\Models;

// CMS User model with 'username', 'first_name', 'last_name' fields
```

#### 6.3 Standard Registration View

```php
// stubs/standard/register.blade.php
// Registration view with single 'name' field
```

#### 6.4 CMS Registration View

```php
// stubs/cms/register.blade.php
// Registration view with 'username', 'first_name', 'last_name' fields
```

### 7. Testing and Validation

1. **Unit Tests**:
   - Test the User model with both standard and CMS configurations
   - Test authentication flows
   - Test profile updates

2. **Integration Tests**:
   - Test the installation script
   - Test the CSS generation
   - Test the conditional file structure

3. **Manual Testing**:
   - Test the full installation process
   - Verify all UI components render correctly
   - Test authentication and user management with both configurations

## Implementation Timeline

1. **Week 1**: Set up basic project structure and modify composer.json
2. **Week 2**: Create installation script and implement package prompts
3. **Week 3**: Replace FluxUI components with ArtisanPack UI components
4. **Week 4**: Implement CMS framework integration and conditional file structure
5. **Week 5**: Testing, bug fixes, and documentation

## Conclusion

This plan outlines the steps to create a custom Laravel Starter Kit that replaces FluxUI components with ArtisanPack UI components, adds required packages, and provides interactive prompts for optional packages and UI customization. The plan also addresses the special case of the CMS framework package, which requires modifications to the user model and related views.

By following this plan, we can create a robust and flexible starter kit that meets the specified requirements while maintaining compatibility with Laravel's core functionality.
