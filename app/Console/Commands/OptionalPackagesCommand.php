<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\multiselect;

class OptionalPackagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artisanpack:optional-packages-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install optional ArtisanPack UI packages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		$packages = multiselect(
			'Which optional packages would you like to install?',
			[
				'artisanpack-ui/cms-framework',
				'artisanpack-ui/code-style',
				'artisanpack-ui/icons',
				'artisanpack-ui/livewire-drag-and-drop',
			]
		);

		if (in_array('artisanpack-ui/cms-framework', $packages)) {
			$this->info('Installing cms-framework and making necessary modifications...');

			// Remove User model and migration
			File::delete(app_path('Models/User.php'));
			$userMigration = collect(glob(database_path('migrations/*_create_users_table.php')))->first();
			if ($userMigration) {
				File::delete($userMigration);
			}

			// Copy stubs (existing code - working correctly)
			File::copy(base_path('stubs/cms-framework/register.blade.php'), resource_path('views/livewire/auth/register.blade.php'));
			File::copy(base_path('stubs/cms-framework/profile.blade.php'), resource_path('views/livewire/settings/profile.blade.php'));
			
			// NEW: Copy additional stub files for complex updates
			File::copy(base_path('stubs/cms-framework/UserFactory.php'), database_path('factories/UserFactory.php'));
			File::copy(base_path('stubs/cms-framework/DatabaseSeeder.php'), database_path('seeders/DatabaseSeeder.php'));
			
			// NEW: Update remaining file references (config/auth.php and test files)
			$this->updateUserModelReferences();

			$this->info('CMS Framework modifications complete.');
		}

		if (!empty($packages)) {
			$this->info('Installing selected optional packages...');
			$packagesForCommand = $packages;
			// If cms-framework is being installed, we need to allow pest-plugin-laravel to be updated
			if (in_array('artisanpack-ui/cms-framework', $packages)) {
				$packagesForCommand[] = '"pestphp/pest-plugin-laravel:*"';
			}

			$command = 'composer require ' . implode(' ', $packagesForCommand) . ' --with-all-dependencies';
			shell_exec($command);
			$this->info('Optional packages installed successfully.');
		}

		// Clean up the stubs directory
		$this->info('Cleaning up installation files...');
		File::deleteDirectory(base_path('stubs'));
		$this->info('Installation complete.');
		return 0;
    }

    /**
     * Update remaining file references from App\Models\User to CMS framework User model.
     */
    protected function updateUserModelReferences(): void
    {
        // Update config/auth.php
        $authConfigPath = config_path('auth.php');
        if (File::exists($authConfigPath)) {
            $authConfig = File::get($authConfigPath);
            $authConfig = str_replace(
                'App\Models\User::class',
                '\ArtisanPackUI\CMSFramework\Models\User::class',
                $authConfig
            );
            File::put($authConfigPath, $authConfig);
        }

        // Update test files
        $testFiles = [
            'tests/Feature/Auth/AuthenticationTest.php',
            'tests/Feature/Auth/EmailVerificationTest.php',
            'tests/Feature/Auth/PasswordConfirmationTest.php',
            'tests/Feature/Auth/PasswordResetTest.php',
            'tests/Feature/DashboardTest.php',
            'tests/Feature/Settings/PasswordUpdateTest.php',
            'tests/Feature/Settings/ProfileUpdateTest.php',
        ];

        foreach ($testFiles as $testFile) {
            $fullPath = base_path($testFile);
            if (File::exists($fullPath)) {
                $content = File::get($fullPath);
                $content = str_replace(
                    'use App\Models\User;',
                    'use ArtisanPackUI\CMSFramework\Models\User;',
                    $content
                );
                File::put($fullPath, $content);
            }
        }
    }
}
