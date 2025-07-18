# ArtisanPack UI Laravel Starter Kit

A custom Laravel starter kit with ArtisanPack UI components for Livewire.

## Features

- Built on Laravel 12.x and Livewire
- ArtisanPack UI components replacing FluxUI
- Enhanced accessibility with artisanpack-ui/accessibility
- Improved security with artisanpack-ui/security
- Interactive installation process
- Optional packages with interactive prompts
- Customizable UI colors
- CMS Framework integration support

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM

## Installation

### Via Composer

```bash
composer create-project artisanpack-ui/livewire-starter-kit
cd livewire-starter-kit
npm install
npm run build
```

During the installation process, you'll be prompted to:

1. Install optional packages:
   - artisanpack-ui/code-style
   - artisanpack-ui/icons
   - artisanpack-ui/cms-framework

2. Customize your UI colors:
   - Primary color
   - Secondary color
   - Accent color

### Manual Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/artisanpack-ui/livewire-starter-kit.git
   cd livewire-starter-kit
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node.js dependencies:
   ```bash
   npm install
   ```

4. Build assets:
   ```bash
   npm run build
   ```

5. Run the installation script:
   ```bash
   php scripts/install.php
   ```

## CMS Framework Integration

If you choose to install the artisanpack-ui/cms-framework package during the installation process:

- The default User model and migration will be removed
- Auth views will be updated to work with the CMS Framework's User model
- The CMS Framework's User model includes username, first_name, and last_name fields

## Development

### Starting the Development Server

```bash
php artisan serve
```

### Compiling Assets

```bash
# Development
npm run dev

# Production
npm run build
```

## License

The ArtisanPack UI Laravel Starter Kit is open-sourced software licensed under the [MIT license](LICENSE.md).