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
				'artisanpack-ui/code-style',
				'artisanpack-ui/icons',
				'artisanpack-ui/livewire-drag-and-drop',
			]
		);

		if (!empty($packages)) {
			$this->info('Installing selected optional packages...');
			$packagesForCommand = $packages;

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
}
