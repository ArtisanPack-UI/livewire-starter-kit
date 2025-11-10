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
- **Modular Structure Option**: Optional modular Laravel architecture using nwidart/laravel-modules
- **Optional Packages**: Choose from additional ArtisanPack UI packages during setup (code-style, icons, hooks, media-library, drag-and-drop)

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM

## Installation

1. Create a new project using Composer:

```bash
composer create-project laravel/livewire-starter-kit your-project-name
```

Or clone the repository:

```bash
git clone https://github.com/your-username/livewire-starter-kit.git your-project-name
cd your-project-name
composer install
```

2. During the installation process, you'll be prompted to:
   - Select optional ArtisanPack UI packages (code-style, icons, hooks, media-library)
   - Choose optional npm packages (drag-and-drop)
   - Decide whether to use a modular Laravel structure

3. Configure your database in the `.env` file (automatically created during installation)

4. Start the development server:

```bash
composer dev
```

This will start the Laravel server, queue worker, logs, and Vite development server concurrently.

For detailed installation instructions, see the [Installation Guide](docs/installation.md).

## Documentation

Comprehensive documentation is available in the `/docs` directory:

- **[Installation](docs/installation.md)** - Complete installation guide
- **[Getting Started](docs/getting-started.md)** - Tutorial and first steps
- **[Configuration](docs/configuration.md)** - Environment and application configuration
- **[Authentication](docs/authentication.md)** - User authentication system
- **[Components](docs/components.md)** - Livewire, Volt, and ArtisanPack UI components
- **[Modular Structure](docs/modular-structure.md)** - Optional modular Laravel architecture
- **[Testing](docs/testing.md)** - Testing with Pest PHP
- **[Deployment](docs/deployment.md)** - Deploy to various platforms
- **[Contributing](docs/contributing.md)** - Contribution guidelines
- **[Troubleshooting](docs/troubleshooting.md)** - Common issues and solutions
- **[FAQ](docs/faq.md)** - Frequently asked questions

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
- **Modules/**: (Optional) Contains modular application structure if enabled

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
