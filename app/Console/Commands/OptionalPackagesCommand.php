<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		$packages = $this->choice(
			'Which optional packages would you like to install? (use comma to separate)',
			[
				'artisanpack-ui/cms-framework',
				'artisanpack-ui/code-style',
				'artisanpack-ui/icons',
				'artisanpack-ui/livewire-drag-and-drop',
			],
			null,
			null,
			true
		);

		if (in_array('artisanpack-ui/cms-framework', $packages)) {
			$this->info('Installing cms-framework and making necessary modifications...');

			// Remove User model and migration
			File::delete(app_path('Models/User.php'));
			$userMigration = collect(glob(database_path('migrations/*_create_users_table.php')))->first();
			if ($userMigration) {
				File::delete($userMigration);
			}

			// Copy stubs
			File::copy(base_path('stubs/cms-framework/register.blade.php'), resource_path('views/livewire/auth/register.blade.php'));
			File::copy(base_path('stubs/cms-framework/profile.blade.php'), resource_path('views/livewire/settings/profile.blade.php'));

			$this->info('CMS Framework modifications complete.');
		}

		if (!empty($packages)) {
			$this->info('Installing selected optional packages...');
			$command = 'composer require ' . implode(' ', $packages);
			shell_exec($command);
			$this->info('Optional packages installed successfully.');
		}

		// Clean up the stubs directory
		$this->info('Cleaning up installation files...');
		File::deleteDirectory(base_path('stubs'));
		$this->info('Installation complete.');
		return 0;
    }
}
