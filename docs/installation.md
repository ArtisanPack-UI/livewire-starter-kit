---
title: Installation
---

# Installation

This guide will walk you through the process of installing and setting up the Livewire Starter Kit.

## Requirements

Before you begin, ensure your development environment meets the following requirements:

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **NPM**: Latest version
- **Database**: MySQL 8.0+, PostgreSQL 13+, or SQLite 3.8+

## Quick Start

### 1. Create New Project

Create a new project using Composer:

```bash
composer create-project laravel/livewire-starter-kit your-project-name
```

Or clone the starter kit repository:

```bash
git clone https://github.com/your-username/livewire-starter-kit.git your-project-name
cd your-project-name
composer install
```

### 2. Interactive Setup

During the `composer create-project` or `composer install` process, you'll be prompted with several setup options:

#### Theme Setup
The theme setup command will automatically configure your color scheme based on your preferences.

#### Optional Packages Selection
You can select which ArtisanPack UI packages to install:
- `artisanpack-ui/code-style` - Code formatting and style utilities
- `artisanpack-ui/icons` - Icon library for UI components
- `artisanpack-ui/hooks` - Useful hooks for extending functionality
- `artisanpack-ui/media-library` - Media management components

#### Optional NPM Packages
You can also select npm packages:
- `@artisanpack-ui/livewire-drag-and-drop` - Drag and drop functionality for Livewire

#### Modular Laravel Structure
You'll be asked if you want to use a modular Laravel structure. If you choose yes:
- `nwidart/laravel-modules` package will be installed
- `mhmiton/laravel-modules-livewire` package will be installed
- Default modules (Admin, Auth, Users) will be created
- Module configuration will be published

### 3. Project Name Configuration

The installer will automatically update your `composer.json` with a package name based on your project directory name (converted to kebab-case).

### 4. Environment Configuration

The `.env` file is automatically created from `.env.example`. Configure your database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Setup

If not already done during installation, run the database migrations:

```bash
php artisan migrate
```

### 6. Start Development Server

Start the development environment:

```bash
composer dev
```

This command will concurrently start:
- Laravel development server
- Queue worker
- Log monitoring
- Vite development server

Your application will be available at `http://localhost:8000`.

## Alternative Installation Methods

### Using Laravel Herd

If you're using Laravel Herd, your application will automatically be available at `https://your-project-name.test` after installation.

### Manual Setup

If you prefer to set up each service manually:

1. Start the Laravel server:
```bash
php artisan serve
```

2. In a new terminal, start the queue worker:
```bash
php artisan queue:work
```

3. In another terminal, start Vite:
```bash
npm run dev
```

4. Monitor logs (optional):
```bash
tail -f storage/logs/laravel.log
```

## Post-Installation Steps

### 1. Re-run Setup Commands (Optional)

If you need to change your initial setup choices, you can run these commands manually:

**Theme Setup:**
```bash
php artisan artisanpack:theme-setup
```

**Optional Packages:**
```bash
php artisan artisanpack:optional-packages-command
```

This allows you to:
- Install additional ArtisanPack UI packages
- Add npm packages
- Enable modular Laravel structure if you didn't initially

### 2. Configure Mail Settings

Update your mail configuration in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Set Up Queue Driver

For production, configure a proper queue driver:

```env
QUEUE_CONNECTION=database
```

Then run:

```bash
php artisan queue:table
php artisan migrate
```

### 3. Configure Session and Cache

For production, consider using Redis or Memcached:

```env
SESSION_DRIVER=redis
CACHE_DRIVER=redis
```

## Troubleshooting

### Common Issues

**Permission Errors**
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**NPM Installation Issues**
```bash
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

**Database Connection Issues**
- Verify database credentials in `.env`
- Ensure database exists
- Check database service is running

### Getting Help

If you encounter issues:

1. Check the [troubleshooting guide](Troubleshooting)
2. Review the [frequently asked questions](Faq)
3. Visit the project repository for support

## Next Steps

Once installation is complete, continue with the [Getting Started](Getting-Started) guide to learn how to use the starter kit effectively.