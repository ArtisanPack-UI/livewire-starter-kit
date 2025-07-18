<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CssGenerationTest extends TestCase
{
    /**
     * Setup before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a temporary directory for CSS output
        $this->tempDir = sys_get_temp_dir() . '/css-generation-test-' . time();
        File::makeDirectory($this->tempDir, 0755, true);
        
        // Set the CSS output path for testing
        config(['artisanpack.css.output_path' => $this->tempDir . '/app.css']);
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
     * Test CSS generation with default colors.
     *
     * @return void
     */
    public function test_css_generation_with_default_colors()
    {
        // Run the CSS generation command with default colors
        Artisan::call('generate:css', [
            '--primary' => '#4f46e5',
            '--secondary' => '#6b7280',
            '--accent' => '#10b981',
        ]);

        // Assert the CSS file was created
        $this->assertTrue(File::exists($this->tempDir . '/app.css'), 'CSS file was not created');

        // Get the CSS content
        $cssContent = File::get($this->tempDir . '/app.css');

        // Assert the CSS contains the default colors
        $this->assertStringContainsString('--primary-color: #4f46e5', $cssContent);
        $this->assertStringContainsString('--secondary-color: #6b7280', $cssContent);
        $this->assertStringContainsString('--accent-color: #10b981', $cssContent);
    }

    /**
     * Test CSS generation with custom colors.
     *
     * @return void
     */
    public function test_css_generation_with_custom_colors()
    {
        // Run the CSS generation command with custom colors
        Artisan::call('generate:css', [
            '--primary' => '#ff0000',
            '--secondary' => '#00ff00',
            '--accent' => '#0000ff',
        ]);

        // Assert the CSS file was created
        $this->assertTrue(File::exists($this->tempDir . '/app.css'), 'CSS file was not created');

        // Get the CSS content
        $cssContent = File::get($this->tempDir . '/app.css');

        // Assert the CSS contains the custom colors
        $this->assertStringContainsString('--primary-color: #ff0000', $cssContent);
        $this->assertStringContainsString('--secondary-color: #00ff00', $cssContent);
        $this->assertStringContainsString('--accent-color: #0000ff', $cssContent);
    }

    /**
     * Test CSS generation with dark mode.
     *
     * @return void
     */
    public function test_css_generation_with_dark_mode()
    {
        // Run the CSS generation command with dark mode
        Artisan::call('generate:css', [
            '--primary' => '#4f46e5',
            '--secondary' => '#6b7280',
            '--accent' => '#10b981',
            '--dark-primary' => '#6366f1',
            '--dark-secondary' => '#9ca3af',
            '--dark-accent' => '#34d399',
            '--enable-dark-mode' => true,
        ]);

        // Assert the CSS file was created
        $this->assertTrue(File::exists($this->tempDir . '/app.css'), 'CSS file was not created');

        // Get the CSS content
        $cssContent = File::get($this->tempDir . '/app.css');

        // Assert the CSS contains both light and dark mode colors
        $this->assertStringContainsString('--primary-color: #4f46e5', $cssContent);
        $this->assertStringContainsString('--secondary-color: #6b7280', $cssContent);
        $this->assertStringContainsString('--accent-color: #10b981', $cssContent);
        
        // Assert the CSS contains dark mode selectors
        $this->assertStringContainsString('.dark', $cssContent);
        $this->assertStringContainsString('--primary-color: #6366f1', $cssContent);
        $this->assertStringContainsString('--secondary-color: #9ca3af', $cssContent);
        $this->assertStringContainsString('--accent-color: #34d399', $cssContent);
    }

    /**
     * Test CSS generation with custom font family.
     *
     * @return void
     */
    public function test_css_generation_with_custom_font_family()
    {
        // Run the CSS generation command with custom font family
        Artisan::call('generate:css', [
            '--primary' => '#4f46e5',
            '--secondary' => '#6b7280',
            '--accent' => '#10b981',
            '--font-family' => 'inter',
        ]);

        // Assert the CSS file was created
        $this->assertTrue(File::exists($this->tempDir . '/app.css'), 'CSS file was not created');

        // Get the CSS content
        $cssContent = File::get($this->tempDir . '/app.css');

        // Assert the CSS contains the custom font family
        $this->assertStringContainsString('font-family: Inter, sans-serif', $cssContent);
    }

    /**
     * Test CSS generation with custom font scaling.
     *
     * @return void
     */
    public function test_css_generation_with_custom_font_scaling()
    {
        // Run the CSS generation command with custom font scaling
        Artisan::call('generate:css', [
            '--primary' => '#4f46e5',
            '--secondary' => '#6b7280',
            '--accent' => '#10b981',
            '--font-scale' => '1.25',
        ]);

        // Assert the CSS file was created
        $this->assertTrue(File::exists($this->tempDir . '/app.css'), 'CSS file was not created');

        // Get the CSS content
        $cssContent = File::get($this->tempDir . '/app.css');

        // Assert the CSS contains scaled font sizes
        // For example, if the base font size is 16px, with a scale of 1.25 it would be 20px
        $this->assertStringContainsString('font-size: 1.25rem', $cssContent);
    }

    /**
     * Test CSS generation with all customizations.
     *
     * @return void
     */
    public function test_css_generation_with_all_customizations()
    {
        // Run the CSS generation command with all customizations
        Artisan::call('generate:css', [
            '--primary' => '#ff0000',
            '--secondary' => '#00ff00',
            '--accent' => '#0000ff',
            '--dark-primary' => '#cc0000',
            '--dark-secondary' => '#00cc00',
            '--dark-accent' => '#0000cc',
            '--enable-dark-mode' => true,
            '--font-family' => 'roboto',
            '--font-scale' => '1.1',
            '--custom-font-family' => 'CustomFont',
            '--custom-font-url' => 'https://fonts.googleapis.com/css2?family=CustomFont:wght@400;700&display=swap',
        ]);

        // Assert the CSS file was created
        $this->assertTrue(File::exists($this->tempDir . '/app.css'), 'CSS file was not created');

        // Get the CSS content
        $cssContent = File::get($this->tempDir . '/app.css');

        // Assert the CSS contains all customizations
        $this->assertStringContainsString('--primary-color: #ff0000', $cssContent);
        $this->assertStringContainsString('--secondary-color: #00ff00', $cssContent);
        $this->assertStringContainsString('--accent-color: #0000ff', $cssContent);
        $this->assertStringContainsString('.dark', $cssContent);
        $this->assertStringContainsString('font-family: Roboto, sans-serif', $cssContent);
        $this->assertStringContainsString('@import url(\'https://fonts.googleapis.com/css2?family=CustomFont:wght@400;700&display=swap\')', $cssContent);
    }

    /**
     * Test CSS generation error handling.
     *
     * @return void
     */
    public function test_css_generation_error_handling()
    {
        // Make the output directory read-only to cause an error
        File::makeDirectory($this->tempDir . '/readonly', 0555, true);
        config(['artisanpack.css.output_path' => $this->tempDir . '/readonly/app.css']);

        // Run the CSS generation command
        $result = Artisan::call('generate:css', [
            '--primary' => '#4f46e5',
            '--secondary' => '#6b7280',
            '--accent' => '#10b981',
        ]);

        // Assert the command failed
        $this->assertNotEquals(0, $result);

        // Assert the CSS file was not created
        $this->assertFalse(File::exists($this->tempDir . '/readonly/app.css'), 'CSS file was created despite error');
    }
}