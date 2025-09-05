---
title: Troubleshooting
---

# Troubleshooting

This guide helps you diagnose and resolve common issues with the Livewire Starter Kit.

## General Debugging

### Enable Debug Mode

For development, enable debug mode in your `.env` file:

```env
APP_DEBUG=true
APP_ENV=local
```

**Never enable debug mode in production** as it can expose sensitive information.

### Clear Caches

When experiencing unexpected behavior, clear all caches:

```bash
# Clear all caches
php artisan optimize:clear

# Or clear specific caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Check Logs

Review application logs for errors:

```bash
# View latest logs
tail -f storage/logs/laravel.log

# View specific log file
cat storage/logs/laravel-2024-01-05.log
```

## Installation Issues

### Composer Installation Fails

**Problem**: Composer install fails with dependency conflicts

**Solutions**:

1. **Update Composer**:
```bash
composer self-update
```

2. **Clear Composer cache**:
```bash
composer clear-cache
```

3. **Use specific PHP version**:
```bash
composer install --ignore-platform-reqs
```

4. **Check PHP extensions**:
```bash
php -m | grep -E "(mbstring|xml|zip|curl|bcmath|gd)"
```

### NPM Installation Issues

**Problem**: NPM install fails or takes too long

**Solutions**:

1. **Clear NPM cache**:
```bash
npm cache clean --force
```

2. **Delete node_modules and reinstall**:
```bash
rm -rf node_modules package-lock.json
npm install
```

3. **Use different registry**:
```bash
npm install --registry https://registry.npmjs.org/
```

### Database Connection Issues

**Problem**: Database connection errors

**Solutions**:

1. **Check database credentials** in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

2. **Test database connection**:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

3. **Create database if it doesn't exist**:
```sql
CREATE DATABASE your_database_name;
```

4. **Check database service is running**:
```bash
# MySQL
sudo service mysql status

# PostgreSQL
sudo service postgresql status
```

## Environment Issues

### Application Key Missing

**Problem**: "No application encryption key has been specified"

**Solution**:
```bash
php artisan key:generate
```

### Permission Errors

**Problem**: Permission denied errors for storage or cache directories

**Solutions**:

1. **Fix permissions**:
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

2. **For development (Linux/Mac)**:
```bash
chmod -R 777 storage bootstrap/cache
```

### Environment Variables Not Loading

**Problem**: Environment variables from `.env` file not working

**Solutions**:

1. **Check `.env` file exists**:
```bash
ls -la .env
```

2. **Verify file format** (no spaces around `=`):
```env
# Correct
APP_NAME="My App"

# Incorrect
APP_NAME = "My App"
```

3. **Clear config cache**:
```bash
php artisan config:clear
```

4. **Check file encoding** (must be UTF-8 without BOM)

## Livewire Issues

### Component Not Updating

**Problem**: Livewire component not responding to user interactions

**Solutions**:

1. **Check for JavaScript errors** in browser console

2. **Verify wire: directives**:
```blade
<!-- Correct -->
<button wire:click="increment">+</button>

<!-- Incorrect -->
<button onclick="increment()">+</button>
```

3. **Add wire:key to dynamic elements**:
```blade
@foreach($items as $item)
    <div wire:key="item-{{ $item->id }}">
        {{ $item->name }}
    </div>
@endforeach
```

4. **Check component mounting**:
```php
public function mount(): void
{
    // Initialize component state
}
```

### Validation Not Working

**Problem**: Form validation not displaying errors

**Solutions**:

1. **Check validation rules**:
```php
protected function rules(): array
{
    return [
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];
}
```

2. **Display errors in template**:
```blade
<x-artisanpack-input wire:model="email" :error="$errors->first('email')" />
```

3. **Use proper validation method**:
```php
public function save(): void
{
    $this->validate(); // This triggers validation
    
    // Save logic here
}
```

### CSRF Token Mismatch

**Problem**: "CSRF token mismatch" errors

**Solutions**:

1. **Include CSRF token in forms**:
```blade
<form>
    @csrf
    <!-- form fields -->
</form>
```

2. **Check session configuration**:
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

3. **Clear sessions**:
```bash
php artisan session:table
php artisan migrate
```

## Volt Issues

### Volt Component Not Found

**Problem**: Volt component not loading or found

**Solutions**:

1. **Check Volt configuration** in `config/volt.php`:
```php
'mount' => [
    resource_path('views/pages'),
    resource_path('views/livewire'),
],
```

2. **Verify file location** matches mount paths

3. **Clear view cache**:
```bash
php artisan view:clear
```

4. **Check component syntax**:
```php
<?php

use function Livewire\Volt\{state};

state(['count' => 0]);

$increment = fn() => $this->count++;

?>

<div>
    <h1>{{ $count }}</h1>
    <button wire:click="increment">+</button>
</div>
```

## ArtisanPack UI Issues

### Components Not Styling Correctly

**Problem**: ArtisanPack UI components not displaying proper styles

**Solutions**:

1. **Check Tailwind CSS is compiled**:
```bash
npm run dev
# or for production
npm run build
```

2. **Verify Tailwind configuration** includes ArtisanPack UI paths:
```js
// tailwind.config.js
content: [
    './resources/**/*.blade.php',
    './vendor/artisanpack-ui/livewire-ui-components/resources/**/*.blade.php',
],
```

3. **Clear compiled assets**:
```bash
rm -rf public/build/*
npm run dev
```

### Component Not Rendering

**Problem**: ArtisanPack UI components not rendering or showing errors

**Solutions**:

1. **Check component exists**:
```bash
php artisan vendor:publish --tag=artisanpack-ui-config
```

2. **Verify correct syntax**:
```blade
<!-- Correct -->
<x-artisanpack-button variant="primary">Click me</x-artisanpack-button>

<!-- Incorrect -->
<artisanpack:button variant="primary">Click me</artisanpack:button>
```

3. **Check for component conflicts** with other packages

## Authentication Issues

### Login Not Working

**Problem**: Users cannot log in with correct credentials

**Solutions**:

1. **Check user exists in database**:
```bash
php artisan tinker
>>> User::where('email', 'test@example.com')->first();
```

2. **Verify password hashing**:
```php
// Check if password is hashed correctly
Hash::check('password', $user->password)
```

3. **Check auth configuration** in `config/auth.php`:
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
```

4. **Clear sessions and try again**:
```bash
php artisan session:flush
```

### Email Verification Not Working

**Problem**: Email verification emails not being sent

**Solutions**:

1. **Check mail configuration** in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

2. **Test mail configuration**:
```bash
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com'); });
```

3. **Check queue configuration** if using queued emails:
```bash
php artisan queue:work
```

### Password Reset Issues

**Problem**: Password reset functionality not working

**Solutions**:

1. **Check password reset table exists**:
```bash
php artisan migrate:status
```

2. **Verify mail configuration** (same as email verification)

3. **Check token expiration** in `config/auth.php`:
```php
'passwords' => [
    'users' => [
        'expire' => 60, // minutes
    ],
],
```

## Performance Issues

### Slow Page Loading

**Problem**: Pages loading slowly in development or production

**Solutions**:

1. **Enable caching** in production:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Check for N+1 query problems**:
```bash
# Install Laravel Debugbar for development
composer require barryvdh/laravel-debugbar --dev
```

3. **Optimize Composer autoloader**:
```bash
composer dump-autoload --optimize
```

4. **Use proper database indexing**:
```php
// In migration
$table->index(['user_id', 'created_at']);
```

### Memory Issues

**Problem**: Out of memory errors

**Solutions**:

1. **Increase PHP memory limit**:
```php
// php.ini
memory_limit = 256M
```

2. **Optimize large dataset queries**:
```php
// Use chunking for large datasets
User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // Process user
    }
});
```

3. **Clear variables in loops**:
```php
foreach ($largeArray as $item) {
    // Process item
    unset($item);
}
```

## Development Server Issues

### Vite Not Working

**Problem**: Vite development server not starting or assets not loading

**Solutions**:

1. **Check Node.js version**:
```bash
node --version  # Should be 18+
```

2. **Install dependencies**:
```bash
npm install
```

3. **Start Vite manually**:
```bash
npm run dev
```

4. **Check Vite configuration** in `vite.config.js`

5. **Clear Vite cache**:
```bash
rm -rf node_modules/.vite
npm run dev
```

### Laravel Server Issues

**Problem**: `php artisan serve` not working or showing errors

**Solutions**:

1. **Check PHP version**:
```bash
php --version  # Should be 8.2+
```

2. **Use specific port**:
```bash
php artisan serve --port=8080
```

3. **Check for port conflicts**:
```bash
lsof -i :8000
```

4. **Use Laravel Herd** instead (macOS/Windows):
- Install Laravel Herd
- Navigate to project directory
- Access via `https://project-name.test`

## Testing Issues

### Tests Not Running

**Problem**: Tests failing to run or giving errors

**Solutions**:

1. **Check testing environment** in `phpunit.xml`:
```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

2. **Create testing database**:
```bash
touch database/database.sqlite
```

3. **Run specific test**:
```bash
php artisan test --filter=testUserCanLogin
```

4. **Check test dependencies**:
```bash
composer require --dev pestphp/pest
```

### Tests Failing Unexpectedly

**Problem**: Previously passing tests now failing

**Solutions**:

1. **Clear test cache**:
```bash
php artisan test --recreate-databases
```

2. **Check for data pollution** between tests:
```php
use RefreshDatabase; // Add this trait to test classes
```

3. **Reset application state**:
```php
beforeEach(function () {
    $this->refreshApplication();
});
```

## Browser-Specific Issues

### JavaScript Errors

**Problem**: JavaScript errors in browser console

**Solutions**:

1. **Check for Livewire Alpine conflicts**:
   - Livewire 3 includes Alpine.js automatically
   - Don't include Alpine.js separately

2. **Verify asset compilation**:
```bash
npm run dev  # or npm run build
```

3. **Clear browser cache** and hard refresh (Ctrl+F5)

4. **Check network tab** for failed asset requests

### CSRF Issues in AJAX Requests

**Problem**: CSRF errors when making AJAX requests

**Solutions**:

1. **Include CSRF token in meta tag**:
```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

2. **Set up AJAX headers**:
```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

## Production Issues

### 500 Internal Server Error

**Problem**: Server error in production

**Solutions**:

1. **Check web server error logs**:
```bash
# Apache
tail -f /var/log/apache2/error.log

# Nginx
tail -f /var/log/nginx/error.log
```

2. **Check PHP error logs**:
```bash
tail -f /var/log/php/php8.2-fpm.log
```

3. **Verify file permissions**:
```bash
sudo chown -R www-data:www-data /var/www/yourapp
sudo chmod -R 755 /var/www/yourapp
```

4. **Check environment configuration**:
```env
APP_ENV=production
APP_DEBUG=false
```

### Assets Not Loading

**Problem**: CSS/JS assets not loading in production

**Solutions**:

1. **Build production assets**:
```bash
npm run build
```

2. **Check web server configuration** for static file serving

3. **Verify asset URLs** in browser network tab

4. **Check file permissions** for public directory

## Getting Help

If you can't resolve an issue:

1. **Search existing issues** in the repository
2. **Check Laravel documentation** for Laravel-specific issues
3. **Check Livewire documentation** for Livewire-specific issues
4. **Create a detailed bug report** with:
   - Steps to reproduce
   - Expected vs actual behavior
   - Environment information
   - Error messages and logs
   - Screenshots if applicable

## Debug Tools

### Useful Debug Packages

For development, consider installing these debug tools:

```bash
# Laravel Debugbar
composer require barryvdh/laravel-debugbar --dev

# Laravel Telescope (for detailed application insights)
composer require laravel/telescope --dev

# Ray (for advanced debugging)
composer require spatie/laravel-ray --dev
```

### Browser Tools

- **Browser Developer Tools** (F12)
- **Laravel Debug Bar** (shows queries, performance, etc.)
- **Vue.js DevTools** (if using Vue components)
- **React Developer Tools** (if using React components)

### Command Line Debug

```bash
# Check PHP configuration
php -i | grep memory_limit

# Check installed extensions
php -m

# Check Composer dependencies
composer show

# Check NPM packages
npm list

# Check disk space
df -h

# Check memory usage
free -m
```

Remember to always test in a development environment first and keep backups before making changes to production systems.