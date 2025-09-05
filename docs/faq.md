---
title: Frequently Asked Questions
---

# Frequently Asked Questions

This page answers common questions about the Livewire Starter Kit.

## General Questions

### What is the Livewire Starter Kit?

The Livewire Starter Kit is a modern Laravel application boilerplate that combines:

- **Laravel 12** - The latest version of the Laravel framework
- **Livewire 3** - Full-stack framework for Laravel
- **Volt** - Functional API for Livewire components
- **ArtisanPack UI** - Modern UI component library
- **Tailwind CSS 4** - Utility-first CSS framework

It provides a solid foundation for building modern web applications with authentication, user management, and a complete UI system.

### Who should use this starter kit?

This starter kit is ideal for:

- **Laravel developers** looking for a modern starting point
- **Teams** wanting consistent UI components and patterns
- **Developers** who prefer server-side rendering over SPA frameworks
- **Projects** requiring rapid development with minimal JavaScript
- **Applications** needing built-in authentication and user management

### What's included out of the box?

The starter kit includes:

- ✅ Complete authentication system (login, register, password reset, email verification)
- ✅ User profile and settings management
- ✅ Responsive layouts with dark mode support
- ✅ ArtisanPack UI component library
- ✅ Testing setup with Pest PHP
- ✅ Development tools and scripts
- ✅ Comprehensive documentation

## Technical Questions

### What PHP version is required?

**PHP 8.2 or higher** is required. This ensures compatibility with Laravel 12 and all modern PHP features used in the starter kit.

### Can I use this with older versions of Laravel?

No, this starter kit is specifically designed for **Laravel 12**. For older Laravel versions, you would need to adapt the code and dependencies accordingly.

### Does this work with Livewire 2?

No, this starter kit requires **Livewire 3**. Livewire 3 includes significant improvements and changes that are not backward compatible with Livewire 2.

### What databases are supported?

The starter kit supports all databases that Laravel supports:

- **MySQL** 8.0+
- **PostgreSQL** 13+
- **SQLite** 3.8+
- **SQL Server** 2017+

### Can I use this without ArtisanPack UI?

While possible, it's not recommended. ArtisanPack UI is deeply integrated into the starter kit's components and layouts. Removing it would require significant refactoring of the UI components.

## Setup and Installation

### Why does installation take so long?

Installation time depends on several factors:

- **Internet speed** for downloading dependencies
- **System performance** for compiling assets
- **Database setup** for running migrations

Typically, installation takes 2-5 minutes on modern systems.

### Can I use this with Laravel Herd?

**Yes!** Laravel Herd is fully supported and recommended for local development. After installation, your project will be automatically available at `https://project-name.test`.

### Do I need to install Node.js?

**Yes**, Node.js 18+ is required for:

- Compiling frontend assets with Vite
- Managing NPM dependencies
- Building production assets

### Can I use Yarn instead of NPM?

Yes, you can use Yarn, but you'll need to:

1. Delete `package-lock.json`
2. Run `yarn install`
3. Update scripts in `package.json` if needed

However, NPM is officially supported and tested.

## Development Questions

### How do I add new pages?

You can add new pages in several ways:

**Option 1: Volt Components**
```bash
php artisan make:volt pages/about
```

**Option 2: Traditional Livewire**
```bash
php artisan make:livewire AboutPage
```

**Option 3: Blade Views**
Create a new route in `routes/web.php` pointing to a Blade view.

### How do I customize the theme?

Theme customization can be done by:

1. **Modifying Tailwind configuration** in `tailwind.config.js`
2. **Updating CSS variables** in `resources/css/app.css`
3. **Customizing ArtisanPack UI components** through configuration
4. **Creating custom components** in `resources/views/components/`

### How do I add authentication to new pages?

Use the `auth` middleware in your routes:

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
});
```

For Volt components, the middleware is applied at the route level.

### Can I add API endpoints?

**Yes!** You can add API endpoints by:

1. Creating API routes in `routes/api.php`
2. Using Laravel Sanctum for API authentication (already included)
3. Creating API controllers and resources
4. Following RESTful conventions

## Customization Questions

### How do I change the default colors?

Modify the Tailwind configuration in `tailwind.config.js`:

```javascript
theme: {
    extend: {
        colors: {
            primary: {
                50: '#your-color-50',
                // ... other shades
                900: '#your-color-900',
            }
        }
    }
}
```

### Can I use a different CSS framework?

While possible, it would require significant changes:

- Remove Tailwind CSS dependencies
- Replace ArtisanPack UI components (which depend on Tailwind)
- Update all existing styles and components
- Modify the build process

This is not recommended as it defeats the purpose of using the starter kit.

### How do I add new form fields to registration?

1. **Add fields to the registration Volt component**
2. **Update validation rules**
3. **Modify the User model** to include new fillable fields
4. **Create a migration** for database changes
5. **Update the registration form view**

See the [Authentication](authentication/registration) guide for detailed steps.

### Can I customize email templates?

**Yes!** Email templates can be customized by:

1. Publishing vendor views: `php artisan vendor:publish --tag=laravel-mail`
2. Customizing templates in `resources/views/emails/`
3. Creating custom mailable classes
4. Modifying notification templates

## Deployment Questions

### Where can I deploy this application?

The starter kit can be deployed to any platform that supports Laravel:

- **Laravel Forge** (recommended)
- **Laravel Vapor** (serverless)
- **Traditional VPS** (DigitalOcean, Linode, etc.)
- **Shared hosting** (with PHP 8.2+ support)
- **Docker containers**
- **Cloud platforms** (AWS, Google Cloud, Azure)

### Do I need to build assets for production?

**Yes!** Always run `npm run build` before deploying to production. This:

- Minifies and optimizes CSS/JS
- Generates production-ready assets
- Ensures optimal performance

### How do I set up SSL certificates?

SSL setup depends on your hosting:

- **Laravel Forge**: Automatic SSL through Let's Encrypt
- **Traditional servers**: Use Certbot or manual certificate installation
- **Cloud platforms**: Usually handled automatically
- **CDN services**: Configure through your CDN provider

### What about database migrations?

Always run migrations in production:

```bash
php artisan migrate --force
```

The `--force` flag bypasses the production environment confirmation.

## Performance Questions

### How fast is this starter kit?

Performance depends on your:

- **Server specifications**
- **Database optimization**
- **Caching configuration**
- **Asset optimization**

With proper setup, expect:

- **Initial page load**: 200-500ms
- **Livewire updates**: 50-200ms
- **Database queries**: <100ms

### Does it support caching?

**Yes!** Multiple caching layers are supported:

- **Route caching**: `php artisan route:cache`
- **Config caching**: `php artisan config:cache`
- **View caching**: `php artisan view:cache`
- **Database caching**: Redis, Memcached
- **HTTP caching**: Through reverse proxies

### Can it handle high traffic?

With proper optimization, yes:

- **Use Redis** for sessions and caching
- **Enable OPcache** for PHP
- **Optimize database** queries and indexing
- **Use a CDN** for static assets
- **Scale horizontally** with load balancers

## Security Questions

### Is this secure out of the box?

The starter kit follows Laravel security best practices:

- ✅ CSRF protection on all forms
- ✅ SQL injection prevention through Eloquent
- ✅ XSS protection with Blade templating
- ✅ Secure password hashing
- ✅ Rate limiting on authentication routes
- ✅ Email verification system

### How do I keep it secure?

Follow these practices:

1. **Keep dependencies updated** regularly
2. **Use HTTPS** in production
3. **Implement proper validation** for all inputs
4. **Follow security headers** best practices
5. **Regular security audits** with `composer audit`
6. **Monitor logs** for suspicious activity

### Does it support two-factor authentication?

Two-factor authentication is not included by default but can be added using packages like:

- **Laravel Fortify** with 2FA features
- **Spatie Laravel Google2FA**
- **PragmaRX Google2FA Laravel**

## Testing Questions

### What testing framework is included?

The starter kit uses **Pest PHP**, a modern testing framework that provides:

- Elegant syntax
- Parallel testing
- Built-in Laravel support
- Extensive assertion library

### How do I run tests?

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage
```

### Are there example tests included?

**Yes!** The starter kit includes tests for:

- User authentication
- Registration process
- Password reset functionality
- Component interactions
- Database operations

## Troubleshooting Questions

### My components aren't updating. What's wrong?

Common causes:

1. **JavaScript errors** in browser console
2. **Missing wire: directives** on interactive elements
3. **Component not properly mounted**
4. **Cached views** need clearing: `php artisan view:clear`

See the [Troubleshooting](troubleshooting) guide for detailed solutions.

### Assets aren't loading in production. Help!

Check these items:

1. **Run `npm run build`** before deployment
2. **Verify file permissions** on public directory
3. **Check web server configuration** for static files
4. **Clear browser cache** and try again

### I'm getting permission errors. How to fix?

Fix file permissions:

```bash
sudo chown -R www-data:www-data /path/to/your/app
sudo chmod -R 755 /path/to/your/app
sudo chmod -R 775 storage bootstrap/cache
```

## Integration Questions

### Can I integrate with external APIs?

**Absolutely!** Laravel provides excellent HTTP client support:

```php
$response = Http::get('https://api.example.com/data');
```

You can also use packages like Guzzle for more complex integrations.

### Does it work with Vue.js or React?

While the starter kit is designed for server-side rendering with Livewire, you can integrate frontend frameworks:

- **Inertia.js** for Vue/React integration
- **Alpine.js** (included with Livewire) for lightweight interactions
- **Custom JavaScript** for specific features

### Can I add a CMS?

Yes, you can add CMS functionality by:

- **Installing Filament** for admin panels
- **Creating custom admin interfaces**
- **Using packages like Nova** (paid)
- **Building custom content management** features

## Licensing Questions

### What license is this under?

The starter kit is released under the **MIT License**, which means:

- ✅ **Commercial use** allowed
- ✅ **Modification** allowed
- ✅ **Distribution** allowed
- ✅ **Private use** allowed
- ⚠️ **No warranty** provided
- ⚠️ **License must be included** in distributions

### Can I use this for commercial projects?

**Yes!** The MIT license allows commercial use without restrictions or royalty payments.

### Do I need to credit the starter kit?

While not required, attribution is appreciated. The license only requires that you include the original license file in your distributions.

## Support Questions

### Where can I get help?

Get help through:

1. **Documentation** - Check this comprehensive guide first
2. **GitHub Issues** - For bug reports and feature requests
3. **GitHub Discussions** - For questions and community support
4. **Laravel Community** - For Laravel-specific questions
5. **Livewire Community** - For Livewire-specific questions

### How do I report bugs?

Report bugs by:

1. **Checking existing issues** to avoid duplicates
2. **Creating a detailed issue** with reproduction steps
3. **Including environment information**
4. **Providing error messages** and logs
5. **Adding screenshots** if applicable

### Can I contribute to the project?

**Yes!** Contributions are welcome:

1. **Fork the repository** on GitHub
2. **Make your changes** following coding standards
3. **Write tests** for new features
4. **Submit a pull request** with detailed description

See the [Contributing](contributing) guide for detailed instructions.

### Is professional support available?

While no official commercial support is provided, you can:

- **Hire Laravel developers** familiar with the stack
- **Consult with the community** for guidance
- **Use Laravel's commercial support** options
- **Engage freelance developers** for custom work

---

## Still have questions?

If your question isn't answered here:

1. **Search the [documentation](installation)** first
2. **Check [GitHub Discussions](https://github.com/your-repo/discussions)**
3. **Browse [existing issues](https://github.com/your-repo/issues)**
4. **Create a new discussion** or issue if needed

We're here to help you succeed with the Livewire Starter Kit! 🚀