# ArtisanPack UI Livewire Starter Kit

## [2.0.0] - Unreleased

### Added
- Non-interactive fallback for `artisanpack:optional-packages-command`: when run under `laravel new` (which passes `--no-interaction`), the command now re-attaches to `/dev/tty` where available so the prompts actually appear, and falls back to a clear "run this manually after install" notice when no terminal is reachable.
- Expanded the optional-packages prompt to include every current ArtisanPack UI package (ai, bing-places, bookings, cms-framework, google, google-business-profile, hooks, icons, media-library, security, visual-editor, plus the code-style dev tools), grouped by category in each label.
- `phpunit/phpunit ^12.0` and `pestphp/pest-plugin-laravel ^4.0` explicitly required to match the L13 test toolchain.
- `config/cache.php` now declares an empty `serializable_classes` allowlist to match L13's hardened deserialization default.
- `config/session.php` now defaults `serialization` to `json` (via `SESSION_SERIALIZATION`). **Upgrading applications:** switching from `php` to `json` invalidates existing session data — plan a rollout accordingly.

### Changed
- **Breaking:** minimum PHP is now **8.3** (dropped 8.2).
- **Breaking:** upgraded to **Laravel 13** (`laravel/framework ^13.0`). The `^12.0|^13.0` compatibility range shipped in 1.0.3 is dropped — v2.x targets L13 only.
- Upgraded companion dependencies to their L13 majors: `laravel/tinker ^3.0`, `laravel/boost ^2.0`, `pestphp/pest ^4.0`, `pestphp/pest-plugin-laravel ^4.0`.
- Bumped Vite to `^7.0` and `laravel-vite-plugin` to `^2.0`; refreshed remaining npm dep floors.
- Bumped ArtisanPack UI dependencies to their L13-ready majors: `artisanpack-ui/accessibility ^2.3.0`, `artisanpack-ui/core ^1.3.0`, `artisanpack-ui/livewire-ui-components ^2.1.0` (drops the transitive `owenvoke/blade-fontawesome` dep that blocked L13), `artisanpack-ui/security ^2.1.0` (major bump).
- CI matrix dropped PHP 8.2; test job now runs 8.3, 8.4, and 8.5.

### Fixed
- `config/database.php` now prefers `Pdo\Mysql::ATTR_SSL_CA` when available (PHP 8.5+) and falls back to `PDO::MYSQL_ATTR_SSL_CA` on 8.3/8.4, silencing the PHP 8.5 deprecation notice on the MySQL and MariaDB connections.

## [1.0.3] - 2026-06-09

### Added
- Laravel 13 support: widened `laravel/framework` constraint to `^12.0|^13.0`. Existing Laravel 12 users are unaffected; Laravel 13 is selectable on PHP 8.3+ via L13's own requirements (#9)

### Changed
- CI/CD workflows brought in line with the rest of the ArtisanPack UI ecosystem (cms-framework, seo):
  - `release.yml` extracted from `ci.yml` as a separate workflow triggered on `v*` tag (test → create GitHub Release with CHANGELOG-extracted notes → notify Packagist)
  - `ci.yml` gains a `lint` job (Pint) and a PHP version matrix (8.2, 8.3, 8.4)
  - Top-level `permissions: contents: read` added to both workflows
  - Removed vestigial `build` job in `ci.yml` (uploaded a `vendor/` artifact that was never consumed)

### Removed
- Untracked auto-generated `config/artisanpack.php` (added to `.gitignore`); the file is regenerated per-environment by `artisanpack:scaffold-config` and contains machine-specific absolute paths

## [1.0.2] - 2026-01-04

### Changed
- Default database changed from SQLite to MySQL
- Reordered post-create-project-cmd scripts to run key:generate before optional packages
- Added second migrate command to run migrations from optional packages

## [1.0.1] - 2026-01-04

### Added
- Tests for theme setup command (ThemeSetupCommandTest)
- Tests for installation process verification (InstallationTest)

### Changed
- Removed composer.lock from version control for fresh dependency resolution on install
- Removed GitHub Actions workflows (project uses GitLab CI)

### Fixed
- Installation errors caused by livewire-ui-components package issues (fixed in livewire-ui-components v1.0.1)

## [1.0.0] - 2026-01-02

### Changed
- Removed Flux UI dependency and related components
- Removed unused sidebar and header layout files
- Updated OptionalPackagesCommand to run artisanpack:scaffold-config at the end of installation

### Fixed
- Test failures caused by view compilation of unused layout files

## [1.0.0-beta1] - 2025-11-09

### Added
- Support for Modular Laravel structure option during project setup
- Ability to rename the composer package based on the name set in `laravel new`
- Support for all available ArtisanPack UI packages (Core, Accessibility, Security, Livewire UI Components, Drag & Drop, etc.)
- Home documentation page (docs/home.md)
- Comprehensive .gitattributes configuration to exclude development files from releases

### Changed
- Expanded and updated project documentation across all doc files

### Removed
- CMS Framework package option (temporarily removed until it reaches beta status)

### Fixed
- Issue with drag and drop package appearing in multiple selection options
- Removed .DS_Store files from repository
- Updated .gitignore to prevent .DS_Store files from being committed

## [0.4.6] - 2025-09-03

- Fixed typo: Changed "CmsFramework" to "CMSFramework" in stub files and code references
- Updated UserFactory.php and DatabaseSeeder.php stub files with correct namespace casing

## [0.4.5] - 2025-09-03

- Implemented comprehensive CMS Framework stub file replacement system (Approach 3)
- Added automatic User model reference updates in config/auth.php and test files
- Created stub files for UserFactory.php and DatabaseSeeder.php with proper CMS Framework model references
- Enhanced OptionalPackagesCommand with updateUserModelReferences() method for complete installation automation

## [0.4.4] - 2025-09-02

- Improved optional packages installation command with multiselect interface using Laravel Prompts
- Updated CMS Framework stub files to use correct User model namespace

## [0.4.3] - 2025-07-23

- Added in installation scripts for generating the theme colors and installing optional packages.

## [0.3.0] - 2025-07-22

- Initial test release of the ArtisanPack UI Livewire Starter Kit package.
