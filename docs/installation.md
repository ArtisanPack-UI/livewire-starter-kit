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

Clone the starter kit repository:

```bash
git clone https://github.com/your-username/livewire-starter-kit.git your-project-name
```

### 2. Navigate to Project

```bash
cd your-project-name
```

### 3. Install Dependencies

Install NPM dependencies:

```bash
npm install
```

### 4. Environment Configuration

Copy the environment file and configure your application:

```bash
cp .env.example .env
```

Generate your application key:

```bash
php artisan key:generate
```

### 5. Database Setup

Configure your database connection in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run the database migrations:

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

### 1. Configure Mail Settings

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

1. Check the [troubleshooting guide](troubleshooting)
2. Review the [frequently asked questions](faq)
3. Visit the project repository for support

## Next Steps

Once installation is complete, continue with the [Getting Started](getting-started) guide to learn how to use the starter kit effectively.