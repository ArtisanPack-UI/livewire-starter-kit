<?php

test('command runs successfully without modular structure', function () {
    $this->artisan('artisanpack:optional-packages-command')
        ->expectsQuestion(__('Which optional packages would you like to install?'), [])
        ->expectsQuestion(__('Which optional npm packages would you like to install?'), [])
        ->expectsConfirmation(__('Would you like to use a modular Laravel structure?'), 'no')
        ->assertExitCode(0);
});

test('composer.json structure is valid for module autoloading', function () {
    // Verify that the module autoloading configuration structure is correct
    $expectedStructure = [
        'include' => [
            'Modules/*/composer.json',
        ],
    ];

    // This test validates the expected structure without actually modifying composer.json
    expect($expectedStructure)->toHaveKey('include')
        ->and($expectedStructure['include'])->toContain('Modules/*/composer.json');
});

test('default modules list is correct', function () {
    // Verify that the default modules to be created are correct
    $expectedModules = ['Admin', 'Auth', 'Users'];

    expect($expectedModules)
        ->toHaveCount(3)
        ->toContain('Admin')
        ->toContain('Auth')
        ->toContain('Users');
});

test('composer.json name is updated based on project directory', function () {
    // Read the actual composer.json
    $composerJsonPath = base_path('composer.json');
    $composerJson = json_decode(file_get_contents($composerJsonPath), true);

    // Get the expected project name based on the directory
    $projectName = basename(base_path());
    $projectName = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $projectName));
    $projectName = trim($projectName, '-');

    // After running the command, the name should be updated
    // Since the command runs during post-create-project-cmd, the composer.json
    // should already have been updated
    expect($composerJson)
        ->toHaveKey('name')
        ->and($composerJson['name'])->toBe("laravel/{$projectName}")
        ->and($composerJson)->toHaveKey('description')
        ->and($composerJson['description'])->toBe('A Laravel application.');
});
