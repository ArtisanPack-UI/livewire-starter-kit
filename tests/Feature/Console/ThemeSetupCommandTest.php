<?php

use Illuminate\Support\Facades\File;

test('theme setup command runs successfully with valid colors', function () {
    $this->artisan('artisanpack:theme-setup')
        ->expectsQuestion('What is your primary color? (e.g., #FFFFFF)', '#3b82f6')
        ->expectsQuestion('What is your secondary color? (e.g., #000000)', '#64748b')
        ->expectsQuestion('What is your accent color? (e.g., #FF0000)', '#f59e0b')
        ->expectsOutput('Generating theme with your selected colors...')
        ->expectsOutput('Theme generated successfully!')
        ->assertExitCode(0);
});

test('theme setup command runs with tailwind color names', function () {
    $this->artisan('artisanpack:theme-setup')
        ->expectsQuestion('What is your primary color? (e.g., #FFFFFF)', 'blue')
        ->expectsQuestion('What is your secondary color? (e.g., #000000)', 'slate')
        ->expectsQuestion('What is your accent color? (e.g., #FF0000)', 'amber')
        ->expectsOutput('Generating theme with your selected colors...')
        ->expectsOutput('Theme generated successfully!')
        ->assertExitCode(0);
});

test('generate theme command creates css file', function () {
    $outputPath = resource_path('css/artisanpack-ui-theme.css');

    // Remove existing file if present
    if (File::exists($outputPath)) {
        File::delete($outputPath);
    }

    $this->artisan('artisanpack:generate-theme', [
        '--primary' => '#3b82f6',
        '--secondary' => '#64748b',
        '--accent' => '#f59e0b',
    ])->assertExitCode(0);

    expect(File::exists($outputPath))->toBeTrue();

    $cssContent = File::get($outputPath);
    expect($cssContent)
        ->toContain('ArtisanPack UI - Generated Theme')
        ->toContain(':root')
        ->toContain('--p-')
        ->toContain('--s-')
        ->toContain('--a-');
});

test('generate theme command works with hex colors', function () {
    $this->artisan('artisanpack:generate-theme', [
        '--primary' => '#ef4444',
        '--secondary' => '#22c55e',
        '--accent' => '#8b5cf6',
    ])->assertExitCode(0);
});

test('generate theme command works with tailwind color names', function () {
    $this->artisan('artisanpack:generate-theme', [
        '--primary' => 'red',
        '--secondary' => 'green',
        '--accent' => 'purple',
    ])->assertExitCode(0);
});
