---
title: Deployment
---

# Deployment

This guide covers deploying the Livewire Starter Kit to various platforms and environments.

## Overview

Deployment options include:

- **[Laravel Forge](Deployment-Forge)** - Managed server deployment
- **[Laravel Vapor](Deployment-Vapor)** - Serverless deployment on AWS
- **[Docker](Deployment-Docker)** - Containerized deployment
- **[Traditional Servers](Deployment-Traditional)** - Manual server setup
- **[Shared Hosting](Deployment-Shared-Hosting)** - Budget hosting options

## Pre-Deployment Checklist

### Environment Configuration

Ensure your production environment is properly configured:

```env
# Application
APP_NAME="Your Application Name"
APP_ENV=production
APP_KEY=your-production-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your_production_database
DB_USERNAME=your_database_user
DB_PASSWORD=your_secure_password

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Cache & Sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-redis-password
REDIS_PORT=6379
```

### Security Configuration

```env
# Security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

### Performance Optimization

Run optimization commands before deployment:

```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Cache events
php artisan event:cache

# Build assets
npm run build
```

## Laravel Forge Deployment

Laravel Forge provides managed server deployment with automated deployments.

### Server Setup

1. **Create Forge Account** at [forge.laravel.com](https://forge.laravel.com)
2. **Connect Server Provider** (DigitalOcean, AWS, etc.)
3. **Create Server** with recommended specifications:
   - **Memory**: 2GB minimum
   - **PHP Version**: 8.2+
   - **Database**: MySQL 8.0+
   - **Web Server**: Nginx

### Site Configuration

1. **Create Site** in Forge dashboard
2. **Configure Domain** and SSL certificate
3. **Set Environment Variables** in site settings
4. **Configure Database** connection

### Deployment Script

Customize the deployment script in Forge:

```bash
cd /home/forge/yourdomain.com

# Put the application in maintenance mode
$FORGE_PHP artisan down

# Pull latest changes
git pull origin $FORGE_SITE_BRANCH

# Install composer dependencies
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install npm dependencies and build assets
npm ci
npm run build

# Run database migrations
$FORGE_PHP artisan migrate --force

# Clear and cache configurations
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache

# Restart queue workers
$FORGE_PHP artisan queue:restart

# Bring the application out of maintenance mode
$FORGE_PHP artisan up
```

### Queue Configuration

Set up queue workers in Forge:

```bash
# Command
php artisan queue:work --sleep=3 --tries=3 --max-time=3600

# Directory
/home/forge/yourdomain.com

# User
forge
```

## Laravel Vapor Deployment

Laravel Vapor provides serverless deployment on AWS.

### Vapor Setup

1. **Install Vapor CLI**:
```bash
composer global require laravel/vapor-cli
```

2. **Login to Vapor**:
```bash
vapor login
```

### Vapor Configuration

Create `vapor.yml` in your project root:

```yaml
id: your-project-id
name: your-app-name
environments:
    production:
        memory: 1024
        cli-memory: 512
        runtime: 'php-8.2'
        variables:
            APP_ENV: production
            APP_DEBUG: false
            CACHE_DRIVER: dynamodb
            SESSION_DRIVER: dynamodb
            QUEUE_CONNECTION: sqs
        secrets:
            - APP_KEY
            - DB_PASSWORD
            - MAIL_PASSWORD
        build:
            - 'composer install --no-dev --classmap-authoritative'
            - 'npm ci && npm run build'
            - 'php artisan event:cache'
    staging:
        memory: 1024
        cli-memory: 512
        runtime: 'php-8.2'
        database: your-staging-database
```

### Database Configuration

For Vapor, configure Aurora Serverless:

```yaml
environments:
    production:
        database: your-production-database
        cache: your-production-cache
```

### Deployment Commands

```bash
# Deploy to staging
vapor deploy staging

# Deploy to production
vapor deploy production

# Run migrations
vapor command production "migrate --force"
```

## Docker Deployment

Containerized deployment using Docker.

### Dockerfile

Create a production Dockerfile:

```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

CMD ["php-fpm"]
```

### Docker Compose

Create `docker-compose.prod.yml`:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/nginx.conf:/etc/nginx/sites-available/default
    networks:
      - app-network
    depends_on:
      - db
      - redis

  nginx:
    image: nginx:alpine
    container_name: nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
      - ./docker/ssl:/etc/nginx/ssl
    networks:
      - app-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: database
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_USER: ${DB_USERNAME}
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - app-network

  redis:
    image: redis:alpine
    container_name: redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    networks:
      - app-network

networks:
  app-network:
    driver: bridge

volumes:
  mysql_data:
    driver: local
```

### Deployment Commands

```bash
# Build and deploy
docker-compose -f docker-compose.prod.yml up -d --build

# Run migrations
docker-compose exec app php artisan migrate --force

# Clear caches
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## Traditional Server Deployment

Manual deployment to a traditional LAMP/LEMP server.

### Server Requirements

- **PHP**: 8.2 or higher
- **Web Server**: Apache or Nginx
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Memory**: 1GB minimum
- **Storage**: 10GB minimum

### Server Setup

#### Install PHP and Extensions

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
                 php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath \
                 php8.2-gd php8.2-redis

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

#### Configure Nginx

Create `/etc/nginx/sites-available/yourapp`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/yourapp/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### SSL Configuration with Let's Encrypt

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Application Deployment

```bash
# Clone repository
git clone https://github.com/your-username/your-app.git /var/www/yourapp
cd /var/www/yourapp

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/yourapp
sudo chmod -R 755 /var/www/yourapp
sudo chmod -R 775 /var/www/yourapp/storage
sudo chmod -R 775 /var/www/yourapp/bootstrap/cache

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --force

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl reload nginx
sudo systemctl restart php8.2-fpm
```

## Zero-Downtime Deployment

Implement zero-downtime deployments using symlinks.

### Directory Structure

```
/var/www/yourapp/
├── current -> releases/20240105120000
├── releases/
│   ├── 20240105120000/
│   ├── 20240105110000/
│   └── 20240105100000/
├── shared/
│   ├── .env
│   └── storage/
└── deploy.sh
```

### Deployment Script

```bash
#!/bin/bash

DEPLOY_PATH="/var/www/yourapp"
REPO_URL="https://github.com/your-username/your-app.git"
BRANCH="main"
RELEASE_NAME=$(date "+%Y%m%d%H%M%S")
RELEASE_PATH="$DEPLOY_PATH/releases/$RELEASE_NAME"

# Create release directory
mkdir -p $RELEASE_PATH

# Clone latest code
git clone -b $BRANCH $REPO_URL $RELEASE_PATH

# Create shared directories if they don't exist
mkdir -p $DEPLOY_PATH/shared/storage

# Link shared files and directories
ln -nfs $DEPLOY_PATH/shared/.env $RELEASE_PATH/.env
ln -nfs $DEPLOY_PATH/shared/storage $RELEASE_PATH/storage

# Install dependencies
cd $RELEASE_PATH
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Run artisan commands
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Update symlink
ln -nfs $RELEASE_PATH $DEPLOY_PATH/current

# Restart services
sudo systemctl reload nginx
sudo systemctl restart php8.2-fpm

# Restart queue workers
php artisan queue:restart

# Clean up old releases (keep last 5)
cd $DEPLOY_PATH/releases
ls -t | tail -n +6 | xargs rm -rf

echo "Deployment completed successfully!"
```

## Monitoring and Maintenance

### Application Monitoring

Set up monitoring for your production application:

```php
// config/logging.php
'channels' => [
    'production' => [
        'driver' => 'stack',
        'channels' => ['daily', 'slack'],
    ],
    
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'critical',
    ],
],
```

### Health Checks

Create health check endpoints:

```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'services' => [
            'database' => DB::connection()->getPdo() ? 'ok' : 'error',
            'redis' => Redis::ping() ? 'ok' : 'error',
        ],
    ]);
});
```

### Backup Strategy

Set up automated backups:

```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="your_database"
BACKUP_PATH="/backups"

# Database backup
mysqldump -u root -p$MYSQL_ROOT_PASSWORD $DB_NAME > $BACKUP_PATH/db_$DATE.sql

# File backup
tar -czf $BACKUP_PATH/files_$DATE.tar.gz /var/www/yourapp/storage

# Upload to S3 (optional)
aws s3 cp $BACKUP_PATH/db_$DATE.sql s3://your-backup-bucket/
aws s3 cp $BACKUP_PATH/files_$DATE.tar.gz s3://your-backup-bucket/

# Clean up local backups older than 7 days
find $BACKUP_PATH -name "*.sql" -mtime +7 -delete
find $BACKUP_PATH -name "*.tar.gz" -mtime +7 -delete
```

## Performance Optimization

### Server-Level Optimizations

```nginx
# Nginx optimizations
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_types text/plain text/css application/json application/javascript text/xml application/xml;

# Enable caching
location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}

# Rate limiting
limit_req_zone $binary_remote_addr zone=login:10m rate=1r/s;
limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
```

### Application-Level Optimizations

```php
// Enable OPcache in production
// php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60

// Configure queue workers
php artisan queue:work --sleep=3 --tries=3 --max-time=3600 --memory=512
```

## Troubleshooting

### Common Issues

**Permission Errors**
```bash
sudo chown -R www-data:www-data /var/www/yourapp
sudo chmod -R 755 /var/www/yourapp
sudo chmod -R 775 /var/www/yourapp/storage
```

**Environment Variables Not Loading**
- Check `.env` file permissions
- Verify file encoding (UTF-8 without BOM)
- Clear configuration cache: `php artisan config:clear`

**Database Connection Errors**
- Verify database credentials
- Check firewall settings
- Ensure database server is accessible

## Security Best Practices

1. **Use HTTPS everywhere** with proper SSL certificates
2. **Regularly update** PHP, server software, and dependencies
3. **Configure firewalls** to restrict access
4. **Use strong passwords** and rotate them regularly
5. **Implement rate limiting** for authentication endpoints
6. **Monitor logs** for suspicious activity
7. **Regular security audits** using tools like `composer audit`

## Next Steps

- Set up [Monitoring](Monitoring) for production applications
- Configure [Backup](Backup) strategies
- Learn about [Performance](Performance) optimization
- Review [Security](Security) best practices