<?php

use Illuminate\Support\Facades\File;

/**
 * These tests verify that the starter kit installation process works correctly.
 * They simulate the post-create-project-cmd scripts defined in composer.json.
 */
test('installation process: environment file exists', function () {
    // The .env file should exist after installation
    expect(File::exists(base_path('.env')))->toBeTrue();
});

test('installation process: required configuration files exist', function () {
    // Verify essential configuration files exist
    expect(File::exists(base_path('.env.example')))->toBeTrue();
    expect(File::exists(base_path('composer.json')))->toBeTrue();
    expect(File::exists(base_path('package.json')))->toBeTrue();
    expect(File::exists(base_path('vite.config.js')))->toBeTrue();
    expect(File::exists(config_path('app.php')))->toBeTrue();
    expect(File::exists(config_path('database.php')))->toBeTrue();
});

test('installation process: app key is set', function () {
    // The APP_KEY should be set in the environment
    expect(config('app.key'))->not->toBeNull();
    expect(config('app.key'))->not->toBe('');
});

test('installation process: database is configured', function () {
    // The database connection should be configured
    expect(config('database.default'))->not->toBeNull();
    expect(config('database.connections'))->not->toBeEmpty();
});

test('installation process: required directories exist', function () {
    // Verify essential directories exist
    expect(File::isDirectory(app_path()))->toBeTrue();
    expect(File::isDirectory(resource_path('views')))->toBeTrue();
    expect(File::isDirectory(database_path('migrations')))->toBeTrue();
    expect(File::isDirectory(public_path()))->toBeTrue();
    expect(File::isDirectory(storage_path()))->toBeTrue();
});

test('installation process: artisan commands are available', function () {
    // Verify that the custom artisan commands are registered
    $this->artisan('list')
        ->expectsOutputToContain('artisanpack:theme-setup')
        ->expectsOutputToContain('artisanpack:optional-packages-command')
        ->assertExitCode(0);
});

test('installation process: migrations can run successfully', function () {
    // Run migrations in a fresh state
    $this->artisan('migrate:fresh', ['--force' => true])
        ->assertExitCode(0);
});

test('installation process: complete theme generation flow', function () {
    // Test the complete flow that runs during installation
    $this->artisan('artisanpack:generate-theme', [
        '--primary' => 'blue',
        '--secondary' => 'slate',
        '--accent' => 'amber',
    ])->assertExitCode(0);

    $outputPath = resource_path('css/artisanpack-ui-theme.css');
    expect(File::exists($outputPath))->toBeTrue();
});

test('installation process: livewire is properly configured', function () {
    // Verify Livewire is configured
    expect(class_exists(\Livewire\Livewire::class))->toBeTrue();
    expect(config('livewire'))->not->toBeNull();
});

test('installation process: artisanpack components are registered', function () {
    // Verify the component prefix is properly set
    expect(config('artisanpack.livewire-ui-components.prefix', 'artisanpack'))->toBe('artisanpack');
});
