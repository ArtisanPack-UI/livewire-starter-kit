# ArtisanPack UI Livewire Starter Kit

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
