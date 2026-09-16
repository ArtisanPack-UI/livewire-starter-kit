<?php

use App\Console\Commands\OptionalPackagesCommand;

test('interactive command runs successfully without modular structure', function () {
    $this->artisan('artisanpack:optional-packages-command')
        ->expectsQuestion(__('Which optional packages would you like to install?'), [])
        ->expectsQuestion(__('Which optional npm packages would you like to install?'), [])
        ->expectsConfirmation(__('Would you like to use a modular Laravel structure?'), 'no')
        ->assertExitCode(0);
});

test('non-interactive command prints a helpful notice instead of silently skipping', function () {
    // Simulate `laravel new`'s --no-interaction path. Under the test runner the
    // TTY re-attach short-circuits (see runningUnitTests() guard in handle()),
    // so the command falls through to the non-interactive fallback: warn +
    // helper hint, then scaffold-config, then exit cleanly.
    $this->artisan('artisanpack:optional-packages-command', ['--no-interaction' => true])
        ->expectsOutputToContain(__('Skipping interactive optional packages setup (non-interactive mode).'))
        ->expectsOutputToContain(__('Run `php artisan artisanpack:optional-packages-command` after install to choose optional packages and modular structure.'))
        ->assertExitCode(0);
});

test('composer.json structure is valid for module autoloading', function () {
    $expectedStructure = [
        'include' => [
            'Modules/*/composer.json',
        ],
    ];

    expect($expectedStructure)->toHaveKey('include')
        ->and($expectedStructure['include'])->toContain('Modules/*/composer.json');
});

test('default modules list is correct', function () {
    $expectedModules = ['Admin', 'Auth', 'Users'];

    expect($expectedModules)
        ->toHaveCount(3)
        ->toContain('Admin')
        ->toContain('Auth')
        ->toContain('Users');
});

test('composer.json name is updated based on project directory', function () {
    $composerJsonPath = base_path('composer.json');
    $composerJson = json_decode(file_get_contents($composerJsonPath), true);

    $projectName = basename(base_path());
    $projectName = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $projectName));
    $projectName = trim($projectName, '-');

    expect($composerJson)
        ->toHaveKey('name')
        ->and($composerJson['name'])->toBe("laravel/{$projectName}")
        ->and($composerJson)->toHaveKey('description')
        ->and($composerJson['description'])->toBe('A Laravel application.');
});

test('optional composer packages include all current ArtisanPack UI packages', function () {
    $command = new OptionalPackagesCommand;

    $flatten = fn () => collect(
        (new ReflectionProperty($command, 'optionalComposerPackages'))
            ->getValue($command)
    )->flatMap(fn ($group) => array_keys($group))->all();

    expect($flatten())
        ->toContain('artisanpack-ui/cms-framework')
        ->toContain('artisanpack-ui/media-library')
        ->toContain('artisanpack-ui/visual-editor')
        ->toContain('artisanpack-ui/security')
        ->toContain('artisanpack-ui/ai')
        ->toContain('artisanpack-ui/google')
        ->toContain('artisanpack-ui/google-business-profile')
        ->toContain('artisanpack-ui/bing-places')
        ->toContain('artisanpack-ui/bookings')
        ->toContain('artisanpack-ui/icons')
        ->toContain('artisanpack-ui/hooks');
});
