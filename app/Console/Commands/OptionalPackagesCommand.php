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
			__('Which optional packages would you like to install?'),
			[
				'artisanpack-ui/code-style',
				'artisanpack-ui/icons',
				'artisanpack-ui/hooks',
				'artisanpack-ui/media-library',
			]
		);

		if (!empty($packages)) {
			$this->info('Installing selected optional packages...');
			$packagesForCommand = $packages;

			$command = 'composer require ' . implode(' ', $packagesForCommand) . ' --with-all-dependencies';
			shell_exec($command);
			$this->info('Optional packages installed successfully.');
		}

		$this->info('Publishing ArtisanPack configuration...');
		shell_exec('php artisan vendor:publish --tag=artisanpack-config');

		$npmPackages = multiselect(
			__('Which optional npm packages would you like to install?'),
			[
				'@artisanpack-ui/livewire-drag-and-drop'
			]
		);

		if (!empty($npmPackages)) {
			$this->info('Installing selected optional npm packages...');
			$npmPackagesForCommand = $npmPackages;
			$command = 'npm install ' . implode(' ', $npmPackagesForCommand);
			shell_exec($command);
			$this->info('Optional npm packages installed successfully.');
		}

		$this->info('Installation complete.');
		return 0;
    }
}
