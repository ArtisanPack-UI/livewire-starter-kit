# Livewire Starter Kit

A Laravel and Livewire starter kit featuring ArtisanPack UI components for rapidly building modern, responsive web applications.

[![Laravel](https://img.shields.io/badge/Laravel-v12.0-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-v3.6-FB70A9?style=flat&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.1-38B2AC?style=flat&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

## Features

- **Modern Stack**: Built with Laravel 12, Livewire 3, Volt, Flux, and Tailwind CSS 4
- **ArtisanPack UI Components**: Pre-built UI components for rapid development
- **Authentication System**: Complete authentication with login, registration, password reset, and email verification
- **User Settings**: Profile, password, and appearance management
- **Responsive Design**: Mobile-first approach with responsive layouts
- **Dark Mode Support**: Built-in light/dark mode with system preference detection
- **Accessibility**: Integrated with ArtisanPack UI accessibility features
- **Security**: Built-in security features and best practices

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM

## Installation

1. Create a new project using Composer:

```bash
git clone https://github.com/your-username/livewire-starter-kit.git your-project-name
```

2. Navigate to your project directory:

```bash
cd your-project-name
```

3. Install NPM dependencies:

```bash
npm install
```

4. Copy the environment file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Configure your database in the `.env` file.

7. Run migrations:

```bash
php artisan migrate
```

8. Start the development server:

```bash
composer dev
```

This will start the Laravel server, queue worker, logs, and Vite development server concurrently.

## Documentation

Comprehensive documentation is available in the `/docs` directory:

- **[Installation](docs/installation)** - Complete installation guide
- **[Getting Started](docs/getting-started)** - Tutorial and first steps
- **[Configuration](docs/configuration)** - Environment and application configuration
- **[Authentication](docs/authentication)** - User authentication system
- **[Components](docs/components)** - Livewire, Volt, and ArtisanPack UI components
- **[Testing](docs/testing)** - Testing with Pest PHP
- **[Deployment](docs/deployment)** - Deploy to various platforms
- **[Contributing](docs/contributing)** - Contribution guidelines
- **[Troubleshooting](docs/troubleshooting)** - Common issues and solutions
- **[FAQ](docs/faq)** - Frequently asked questions

## Usage

### Development

For local development, use the following command:

```bash
composer dev
```

This will start the Laravel server, queue worker, logs, and Vite development server concurrently.

### Building for Production

To build assets for production:

```bash
npm run build
```

### Running Tests

```bash
composer test
```

See the [Testing](docs/testing) documentation for comprehensive testing guides.

## Project Structure

The starter kit follows Laravel's standard directory structure with some additions:

- **app/**: Contains the core code of the application
- **resources/views/components/**: Contains ArtisanPack UI components
- **resources/views/livewire/**: Contains Livewire components
- **resources/css/**: Contains CSS files including ArtisanPack UI theme
- **routes/**: Contains route definitions

## ArtisanPack UI Components

The starter kit includes various ArtisanPack UI components:

- Navigation components
- Form elements
- Buttons and links
- Layout components
- Toast notifications
- Icons
- And more

## Configuration

### Theme Customization

You can customize the theme by modifying the `resources/css/artisanpack-ui-theme.css` file.

### Application Configuration

Standard Laravel configuration files are located in the `config/` directory.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [ArtisanPack UI](https://github.com/artisanpack-ui)
- [Laravel](https://laravel.com)
- [Livewire](https://livewire.laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [DaisyUI](https://daisyui.com)
