---
title: Configuration
---

# Configuration

This guide covers all configuration options available in the Livewire Starter Kit.

## Environment Configuration

### Application Settings

Configure basic application settings in your `.env` file:

```env
# Application
APP_NAME="Your Application Name"
APP_ENV=local
APP_KEY=base64:your-application-key
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file
```

### Database Configuration

Configure your database connection:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**Supported Database Drivers:**
- `mysql` - MySQL/MariaDB
- `pgsql` - PostgreSQL
- `sqlite` - SQLite
- `sqlsrv` - SQL Server

### Mail Configuration

Configure email settings for authentication and notifications:

```env
# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Supported Mail Drivers:**
- `smtp` - SMTP server
- `ses` - Amazon SES
- `mailgun` - Mailgun
- `postmark` - Postmark
- `log` - Log driver (development)

### Cache Configuration

Configure caching for improved performance:

```env
# Cache
CACHE_STORE=file
CACHE_PREFIX=

# Redis (if using Redis)
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Supported Cache Drivers:**
- `array` - In-memory (testing only)
- `database` - Database storage
- `file` - File system storage
- `memcached` - Memcached
- `redis` - Redis
- `dynamodb` - DynamoDB

### Session Configuration

Configure session handling:

```env
# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

**Supported Session Drivers:**
- `file` - File storage
- `cookie` - Cookie storage
- `database` - Database storage
- `memcached` - Memcached
- `redis` - Redis

### Queue Configuration

Configure background job processing:

```env
# Queue
QUEUE_CONNECTION=database

# Redis Queue (if using Redis)
REDIS_QUEUE_CONNECTION=default
```

**Supported Queue Drivers:**
- `sync` - Synchronous (immediate processing)
- `database` - Database storage
- `beanstalkd` - Beanstalkd
- `sqs` - Amazon SQS
- `redis` - Redis

### Broadcasting Configuration

Configure real-time broadcasting:

```env
# Broadcasting
BROADCAST_CONNECTION=log

# Pusher (if using Pusher)
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

### File Storage Configuration

Configure file storage:

```env
# Filesystems
FILESYSTEM_DISK=local

# AWS S3 (if using S3)
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## Configuration Files

### Application Configuration (`config/app.php`)

Key configuration options:

```php
return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    'cipher' => 'AES-256-CBC',
    
    // Service providers
    'providers' => ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
        App\Providers\VoltServiceProvider::class,
    ])->toArray(),
];
```

### Database Configuration (`config/database.php`)

Database connections and settings:

```php
'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'laravel'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
],
```

### Authentication Configuration (`config/auth.php`)

Authentication guards and providers:

```php
'defaults' => [
    'guard' => env('AUTH_GUARD', 'web'),
    'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
],

'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => env('AUTH_MODEL', App\Models\User::class),
    ],
],
```

## Theme Configuration

### Tailwind CSS Configuration

The starter kit uses Tailwind CSS 4. Configuration is in `tailwind.config.js`:

```javascript
import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
```

### ArtisanPack UI Theme

Customize the ArtisanPack UI theme in `resources/css/artisanpack-ui-theme.css`:

```css
:root {
    /* Primary colors */
    --color-primary-50: #eff6ff;
    --color-primary-500: #3b82f6;
    --color-primary-600: #2563eb;
    --color-primary-700: #1d4ed8;
    
    /* Gray colors */
    --color-gray-50: #f9fafb;
    --color-gray-100: #f3f4f6;
    --color-gray-500: #6b7280;
    --color-gray-900: #111827;
}
```

### Custom CSS Variables

You can define custom CSS variables for consistent theming:

```css
:root {
    /* Custom spacing */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    
    /* Custom borders */
    --border-radius: 0.5rem;
    --border-width: 1px;
    
    /* Custom shadows */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
```

## Livewire Configuration

### Global Configuration

Configure Livewire in `config/livewire.php`:

```php
return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'components.layouts.app',
    'lazy_placeholder' => null,
    'temporary_file_upload' => [
        'disk' => null,
        'rules' => null,
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => ['png', 'gif', 'bmp', 'svg', 'wav', 'mp4'],
        'max_upload_time' => 5,
    ],
    'render_on_redirect' => false,
    'legacy_model_binding' => false,
    'inject_assets' => true,
    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],
    'pagination_theme' => 'tailwind',
];
```

### Volt Configuration

Configure Volt in `config/volt.php`:

```php
return [
    'mount' => [
        resource_path('views/pages'),
        resource_path('views/livewire'),
    ],
];
```

## Production Configuration

### Optimization Commands

Run these commands for production optimization:

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Cache events
php artisan event:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### Environment Variables

Production environment settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=your-production-key

# Use database or Redis for sessions/cache
SESSION_DRIVER=database
CACHE_STORE=redis
QUEUE_CONNECTION=redis

# Configure proper mail driver
MAIL_MAILER=ses

# Use HTTPS
APP_URL=https://yourdomain.com
```

### Security Headers

Configure security headers in your web server or middleware:

```php
// In a middleware or service provider
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
```

## Custom Commands Configuration

The starter kit includes custom Artisan commands for setup and configuration:

### Theme Setup Command

Generate and configure your application's color scheme:

```bash
php artisan artisanpack:theme-setup
```

This command automatically sets up your ArtisanPack UI theme based on your preferences. The generated theme configuration is stored in `resources/css/artisanpack-ui-theme.css`.

### Optional Packages Command

Install and configure optional ArtisanPack UI packages:

```bash
php artisan artisanpack:optional-packages-command
```

This interactive command allows you to:

#### 1. Select Composer Packages
Choose from the following ArtisanPack UI packages:
- `artisanpack-ui/code-style` - Code formatting and style utilities
- `artisanpack-ui/icons` - Comprehensive icon library
- `artisanpack-ui/hooks` - Extensibility hooks system
- `artisanpack-ui/media-library` - Media management components

#### 2. Select NPM Packages
Choose from optional frontend packages:
- `@artisanpack-ui/livewire-drag-and-drop` - Drag and drop functionality

#### 3. Enable Modular Structure
Optionally enable a modular Laravel structure using:
- `nwidart/laravel-modules` - Module management package
- `mhmiton/laravel-modules-livewire` - Livewire integration for modules

When enabled, the command will:
- Install required packages
- Publish configuration files
- Create default modules (Admin, Auth, Users)
- Update `composer.json` for module autoloading
- Run `composer dump-autoload`

#### 4. Update Project Name
The command automatically updates your `composer.json` package name based on your project directory name (converted to kebab-case format).

### Re-running Commands

You can re-run these commands at any time to:
- Add additional packages you didn't initially select
- Enable modular structure after initial setup
- Update theme configuration

## Troubleshooting Configuration

### Common Issues

**Configuration Cache Issues**
```bash
php artisan config:clear
php artisan cache:clear
```

**Permission Issues**
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**Environment File Issues**
- Ensure `.env` file exists and is readable
- Check for syntax errors in `.env`
- Verify environment variables are properly quoted

### Configuration Validation

Test your configuration:

```bash
# Test database connection
php artisan migrate:status

# Test mail configuration
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });

# Test cache
php artisan tinker
>>> Cache::put('test', 'value', 60);
>>> Cache::get('test');

# Test queue
php artisan queue:work --once
```

## Next Steps

- Learn about [Authentication](authentication) configuration
- Explore [Component](components) customization
- Understand [Modular Structure](modular-structure) for organizing large applications
- Review [Deployment](deployment) configuration
- Check [Testing](testing) configuration