<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class FileStructureTest extends TestCase
{
    /**
     * Setup before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a temporary directory for testing
        $this->tempDir = sys_get_temp_dir() . '/file-structure-test-' . time();
        File::makeDirectory($this->tempDir, 0755, true);
    }

    /**
     * Cleanup after each test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // Remove the temporary directory
        File::deleteDirectory($this->tempDir);

        parent::tearDown();
    }

    /**
     * Test that the required directories exist.
     *
     * @return void
     */
    public function test_required_directories_exist()
    {
        // List of required directories
        $requiredDirectories = [
            'app',
            'app/Models',
            'bootstrap',
            'config',
            'database',
            'database/migrations',
            'public',
            'resources',
            'resources/views',
            'routes',
            'scripts',
            'storage',
            'tests',
            'stubs',
            'stubs/standard',
            'stubs/cms-compatible',
        ];

        // Check each required directory
        foreach ($requiredDirectories as $directory) {
            $this->assertTrue(
                File::isDirectory(base_path($directory)),
                "Required directory '{$directory}' does not exist"
            );
        }
    }

    /**
     * Test that the required files exist.
     *
     * @return void
     */
    public function test_required_files_exist()
    {
        // List of required files
        $requiredFiles = [
            'composer.json',
            'package.json',
            'scripts/install.php',
            'stubs/standard/User.php',
            'stubs/standard/create_users_table.php',
            'stubs/standard/register.blade.php',
            'stubs/standard/login.blade.php',
            'stubs/standard/forgot-password.blade.php',
            'stubs/standard/reset-password.blade.php',
            'stubs/standard/profile.blade.php',
            'stubs/cms-compatible/register.blade.php',
            'stubs/cms-compatible/login.blade.php',
            'stubs/cms-compatible/forgot-password.blade.php',
            'stubs/cms-compatible/reset-password.blade.php',
            'stubs/cms-compatible/profile.blade.php',
        ];

        // Check each required file
        foreach ($requiredFiles as $file) {
            $this->assertTrue(
                File::exists(base_path($file)),
                "Required file '{$file}' does not exist"
            );
        }
    }

    /**
     * Test that the composer.json file has the required dependencies.
     *
     * @return void
     */
    public function test_composer_json_has_required_dependencies()
    {
        // Get the composer.json content
        $composerJson = json_decode(File::get(base_path('composer.json')), true);

        // Check required dependencies
        $requiredDependencies = [
            'laravel/framework',
            'laravel/tinker',
            'livewire/volt',
            'artisanpack-ui/livewire-ui-components',
            'artisanpack-ui/accessibility',
            'artisanpack-ui/security',
        ];

        foreach ($requiredDependencies as $dependency) {
            $this->assertArrayHasKey(
                $dependency,
                $composerJson['require'],
                "Required dependency '{$dependency}' is missing from composer.json"
            );
        }
    }

    /**
     * Test that the package.json file has the required dependencies.
     *
     * @return void
     */
    public function test_package_json_has_required_dependencies()
    {
        // Get the package.json content
        $packageJson = json_decode(File::get(base_path('package.json')), true);

        // Check required dependencies
        $requiredDependencies = [
            'tailwindcss',
            'autoprefixer',
            'postcss',
        ];

        foreach ($requiredDependencies as $dependency) {
            $this->assertTrue(
                isset($packageJson['devDependencies'][$dependency]) || isset($packageJson['dependencies'][$dependency]),
                "Required dependency '{$dependency}' is missing from package.json"
            );
        }
    }

    /**
     * Test that the standard stubs are valid.
     *
     * @return void
     */
    public function test_standard_stubs_are_valid()
    {
        // Check User.php stub
        $userStub = File::get(base_path('stubs/standard/User.php'));
        $this->assertStringContainsString('namespace App\\Models;', $userStub);
        $this->assertStringContainsString('class User', $userStub);

        // Check create_users_table.php stub
        $migrationStub = File::get(base_path('stubs/standard/create_users_table.php'));
        $this->assertStringContainsString('Schema::create(\'users\'', $migrationStub);
        $this->assertStringContainsString('$table->string(\'name\');', $migrationStub);
        $this->assertStringContainsString('$table->string(\'email\')->unique();', $migrationStub);

        // Check auth view stubs
        $authViews = [
            'register.blade.php',
            'login.blade.php',
            'forgot-password.blade.php',
            'reset-password.blade.php',
        ];

        foreach ($authViews as $view) {
            $viewStub = File::get(base_path('stubs/standard/' . $view));
            $this->assertStringContainsString('<x-artisanpack-', $viewStub);
        }

        // Check profile view stub
        $profileStub = File::get(base_path('stubs/standard/profile.blade.php'));
        $this->assertStringContainsString('<x-artisanpack-', $profileStub);
    }

    /**
     * Test that the CMS-compatible stubs are valid.
     *
     * @return void
     */
    public function test_cms_compatible_stubs_are_valid()
    {
        // Check auth view stubs
        $authViews = [
            'register.blade.php',
            'login.blade.php',
            'forgot-password.blade.php',
            'reset-password.blade.php',
        ];

        foreach ($authViews as $view) {
            $viewStub = File::get(base_path('stubs/cms-compatible/' . $view));
            $this->assertStringContainsString('<x-artisanpack-', $viewStub);
        }

        // Check profile view stub
        $profileStub = File::get(base_path('stubs/cms-compatible/profile.blade.php'));
        $this->assertStringContainsString('<x-artisanpack-', $profileStub);

        // Check for CMS-specific fields in register view
        $registerStub = File::get(base_path('stubs/cms-compatible/register.blade.php'));
        $this->assertStringContainsString('username', $registerStub);
        $this->assertStringContainsString('first_name', $registerStub);
        $this->assertStringContainsString('last_name', $registerStub);
    }

    /**
     * Test file structure creation with standard setup.
     *
     * @return void
     */
    public function test_file_structure_creation_with_standard_setup()
    {
        // Create directories for testing
        File::makeDirectory($this->tempDir . '/app/Models', 0755, true);
        File::makeDirectory($this->tempDir . '/database/migrations', 0755, true);
        File::makeDirectory($this->tempDir . '/resources/views/livewire/auth', 0755, true);
        File::makeDirectory($this->tempDir . '/resources/views/livewire/settings', 0755, true);
        File::makeDirectory($this->tempDir . '/stubs/standard', 0755, true);

        // Copy stub files to the temporary directory
        File::copy(
            base_path('stubs/standard/User.php'),
            $this->tempDir . '/stubs/standard/User.php'
        );
        File::copy(
            base_path('stubs/standard/create_users_table.php'),
            $this->tempDir . '/stubs/standard/create_users_table.php'
        );
        File::copy(
            base_path('stubs/standard/register.blade.php'),
            $this->tempDir . '/stubs/standard/register.blade.php'
        );
        File::copy(
            base_path('stubs/standard/profile.blade.php'),
            $this->tempDir . '/stubs/standard/profile.blade.php'
        );

        // Simulate standard setup
        File::copy(
            $this->tempDir . '/stubs/standard/User.php',
            $this->tempDir . '/app/Models/User.php'
        );
        File::copy(
            $this->tempDir . '/stubs/standard/create_users_table.php',
            $this->tempDir . '/database/migrations/2014_10_12_000000_create_users_table.php'
        );
        File::copy(
            $this->tempDir . '/stubs/standard/register.blade.php',
            $this->tempDir . '/resources/views/livewire/auth/register.blade.php'
        );
        File::copy(
            $this->tempDir . '/stubs/standard/profile.blade.php',
            $this->tempDir . '/resources/views/livewire/settings/profile.blade.php'
        );

        // Assert files were copied correctly
        $this->assertTrue(File::exists($this->tempDir . '/app/Models/User.php'));
        $this->assertTrue(File::exists($this->tempDir . '/database/migrations/2014_10_12_000000_create_users_table.php'));
        $this->assertTrue(File::exists($this->tempDir . '/resources/views/livewire/auth/register.blade.php'));
        $this->assertTrue(File::exists($this->tempDir . '/resources/views/livewire/settings/profile.blade.php'));

        // Assert file contents are correct
        $userContent = File::get($this->tempDir . '/app/Models/User.php');
        $this->assertStringContainsString('namespace App\\Models;', $userContent);
    }

    /**
     * Test file structure creation with CMS Framework setup.
     *
     * @return void
     */
    public function test_file_structure_creation_with_cms_framework_setup()
    {
        // Create directories for testing
        File::makeDirectory($this->tempDir . '/app/Models', 0755, true);
        File::makeDirectory($this->tempDir . '/database/migrations', 0755, true);
        File::makeDirectory($this->tempDir . '/resources/views/livewire/auth', 0755, true);
        File::makeDirectory($this->tempDir . '/resources/views/livewire/settings', 0755, true);
        File::makeDirectory($this->tempDir . '/stubs/cms-compatible', 0755, true);

        // Create a dummy User model file
        File::put($this->tempDir . '/app/Models/User.php', '<?php namespace App\\Models; class User {}');
        
        // Create a dummy migration file
        File::put($this->tempDir . '/database/migrations/2014_10_12_000000_create_users_table.php', '<?php class CreateUsersTable {}');

        // Copy stub files to the temporary directory
        File::copy(
            base_path('stubs/cms-compatible/register.blade.php'),
            $this->tempDir . '/stubs/cms-compatible/register.blade.php'
        );
        File::copy(
            base_path('stubs/cms-compatible/profile.blade.php'),
            $this->tempDir . '/stubs/cms-compatible/profile.blade.php'
        );

        // Simulate CMS Framework setup (remove User model and migration)
        File::delete($this->tempDir . '/app/Models/User.php');
        File::delete($this->tempDir . '/database/migrations/2014_10_12_000000_create_users_table.php');

        // Copy CMS-compatible views
        File::copy(
            $this->tempDir . '/stubs/cms-compatible/register.blade.php',
            $this->tempDir . '/resources/views/livewire/auth/register.blade.php'
        );
        File::copy(
            $this->tempDir . '/stubs/cms-compatible/profile.blade.php',
            $this->tempDir . '/resources/views/livewire/settings/profile.blade.php'
        );

        // Assert User model and migration were removed
        $this->assertFalse(File::exists($this->tempDir . '/app/Models/User.php'));
        $this->assertFalse(File::exists($this->tempDir . '/database/migrations/2014_10_12_000000_create_users_table.php'));

        // Assert CMS-compatible views were copied
        $this->assertTrue(File::exists($this->tempDir . '/resources/views/livewire/auth/register.blade.php'));
        $this->assertTrue(File::exists($this->tempDir . '/resources/views/livewire/settings/profile.blade.php'));

        // Assert file contents are correct
        $registerContent = File::get($this->tempDir . '/resources/views/livewire/auth/register.blade.php');
        $this->assertStringContainsString('username', $registerContent);
        $this->assertStringContainsString('first_name', $registerContent);
        $this->assertStringContainsString('last_name', $registerContent);
    }
}