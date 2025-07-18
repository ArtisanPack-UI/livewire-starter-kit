<?php

// Bootstrap Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Process;
use Laravel\Prompts\Prompts;

// Helper function to run shell commands with enhanced error handling
function runCommand($command, $message = null, $fallbackCommand = null, $criticalOperation = false) {
    if ($message) {
        echo $message . PHP_EOL;
    }

    try {
        $process = Process::run($command);

        if ($process->successful()) {
            echo $process->output() . PHP_EOL;
            return true;
        } else {
            $errorOutput = $process->errorOutput();
            echo "Error: " . $errorOutput . PHP_EOL;
            
            // Provide troubleshooting suggestions based on common errors
            if (strpos($errorOutput, 'composer') !== false) {
                echo "Troubleshooting: Make sure Composer is installed and accessible in your PATH." . PHP_EOL;
                echo "Try running 'composer --version' to verify your installation." . PHP_EOL;
            } elseif (strpos($errorOutput, 'permission') !== false) {
                echo "Troubleshooting: This operation requires elevated permissions." . PHP_EOL;
                echo "Try running the command with sudo or as an administrator." . PHP_EOL;
            } elseif (strpos($errorOutput, 'not found') !== false) {
                echo "Troubleshooting: The command or file was not found." . PHP_EOL;
                echo "Check if the required dependencies are installed." . PHP_EOL;
            }
            
            // Try fallback command if provided
            if ($fallbackCommand) {
                echo "Attempting fallback operation..." . PHP_EOL;
                return runCommand($fallbackCommand, null, null, $criticalOperation);
            }
            
            // Handle critical operation failures
            if ($criticalOperation) {
                echo "Critical operation failed. Installation cannot proceed." . PHP_EOL;
                echo "Please resolve the issues above and try again." . PHP_EOL;
                exit(1);
            }
            
            return false;
        }
    } catch (\Exception $e) {
        echo "Exception occurred: " . $e->getMessage() . PHP_EOL;
        
        // Try fallback command if provided
        if ($fallbackCommand) {
            echo "Attempting fallback operation..." . PHP_EOL;
            return runCommand($fallbackCommand, null, null, $criticalOperation);
        }
        
        // Handle critical operation failures
        if ($criticalOperation) {
            echo "Critical operation failed. Installation cannot proceed." . PHP_EOL;
            echo "Please resolve the issues above and try again." . PHP_EOL;
            exit(1);
        }
        
        return false;
    }
}

echo "🚀 Setting up your ArtisanPack UI Laravel Starter Kit..." . PHP_EOL;

// Check for existing CMS Framework installation
$cmsFrameworkDetected = false;
$cmsFrameworkVersion = null;

try {
    // Check if composer.json exists and contains CMS Framework
    if (file_exists(__DIR__ . '/../composer.json')) {
        $composerJson = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);
        if (isset($composerJson['require']['artisanpack-ui/cms-framework'])) {
            $cmsFrameworkDetected = true;
            $cmsFrameworkVersion = $composerJson['require']['artisanpack-ui/cms-framework'];
            echo "Detected existing CMS Framework installation (version: $cmsFrameworkVersion)" . PHP_EOL;
        }
    }
    
    // Check if the CMS Framework User model exists
    if (file_exists(__DIR__ . '/../vendor/artisanpack-ui/cms-framework/src/Models/User.php')) {
        $cmsFrameworkDetected = true;
        echo "Detected CMS Framework User model" . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "Warning: Error while checking for existing CMS Framework: " . $e->getMessage() . PHP_EOL;
}

// Prompt for optional packages with enhanced descriptions and validation
echo "Optional Packages Selection" . PHP_EOL;
echo "------------------------" . PHP_EOL;
echo "The following packages are optional but recommended for a complete experience." . PHP_EOL;

$installCodeStyle = Prompts::confirm(
    label: 'Would you like to install the artisanpack-ui/code-style package?',
    default: false,
    hint: 'Provides code style rules, linting, and formatting tools for consistent code quality.'
);

$installIcons = Prompts::confirm(
    label: 'Would you like to install the artisanpack-ui/icons package?',
    default: false,
    hint: 'Includes a comprehensive set of SVG icons optimized for ArtisanPack UI components.'
);

// For CMS Framework, provide more detailed information due to its significant impact
echo PHP_EOL . "CMS Framework Information:" . PHP_EOL;
echo "The CMS Framework package provides a complete content management system with:" . PHP_EOL;
echo "- Custom User model with extended fields (username, first_name, last_name)" . PHP_EOL;
echo "- Role and permission management" . PHP_EOL;
echo "- Content modeling and management tools" . PHP_EOL;
echo "- Admin dashboard integration" . PHP_EOL;
echo "Note: Installing this package will remove the default User model and migrations." . PHP_EOL;

// Define compatible versions
$compatibleVersions = [
    'min' => '1.0.0',
    'max' => '2.0.0',
    'recommended' => '1.5.0'
];

if ($cmsFrameworkDetected) {
    echo PHP_EOL . "CMS Framework is already installed or detected in your project." . PHP_EOL;
    
    // Check version compatibility
    $isCompatible = true;
    $compatibilityMessage = '';
    
    if ($cmsFrameworkVersion) {
        // Remove any constraints like ^, ~, etc.
        $cleanVersion = preg_replace('/[^0-9.]/', '', $cmsFrameworkVersion);
        
        if (version_compare($cleanVersion, $compatibleVersions['min'], '<')) {
            $isCompatible = false;
            $compatibilityMessage = "Warning: Your CMS Framework version ($cleanVersion) is older than the minimum compatible version ({$compatibleVersions['min']})." . PHP_EOL;
            $compatibilityMessage .= "Upgrading is strongly recommended to ensure compatibility with this starter kit.";
        } elseif (version_compare($cleanVersion, $compatibleVersions['max'], '>=')) {
            $isCompatible = false;
            $compatibilityMessage = "Warning: Your CMS Framework version ($cleanVersion) is newer than the maximum tested version ({$compatibleVersions['max']})." . PHP_EOL;
            $compatibilityMessage .= "Some features may not work as expected.";
        }
        
        if (!$isCompatible) {
            echo $compatibilityMessage . PHP_EOL;
        } else {
            echo "Your CMS Framework version ($cleanVersion) is compatible with this starter kit." . PHP_EOL;
        }
    }
    
    $cmsFrameworkOptions = [
        'keep' => 'Keep existing installation',
        'upgrade' => 'Upgrade to latest compatible version (' . $compatibleVersions['recommended'] . ')',
        'remove' => 'Remove CMS Framework',
        'migrate' => 'Run migration tools for existing project'
    ];
    
    $cmsFrameworkAction = Prompts::select(
        label: 'What would you like to do with the existing CMS Framework?',
        options: $cmsFrameworkOptions,
        default: $isCompatible ? 'keep' : 'upgrade',
        hint: 'Upgrading or removing may affect your existing data and functionality.'
    );
    
    $installCmsFramework = ($cmsFrameworkAction !== 'remove');
    $upgradeCmsFramework = ($cmsFrameworkAction === 'upgrade');
    $removeCmsFramework = ($cmsFrameworkAction === 'remove');
    $migrateCmsFramework = ($cmsFrameworkAction === 'migrate');
    
    if ($migrateCmsFramework) {
        echo "Preparing to run migration tools for CMS Framework..." . PHP_EOL;
        
        // Ask which migration tools to run
        $migrationOptions = [
            'database' => 'Database schema migration',
            'views' => 'View templates migration',
            'config' => 'Configuration files migration',
            'all' => 'All of the above'
        ];
        
        $migrationType = Prompts::select(
            label: 'Which migration tools would you like to run?',
            options: $migrationOptions,
            default: 'all',
            hint: 'Select the components you want to migrate to the latest version.'
        );
        
        // Set flags based on selection
        $migrateDatabaseSchema = ($migrationType === 'database' || $migrationType === 'all');
        $migrateViews = ($migrationType === 'views' || $migrationType === 'all');
        $migrateConfig = ($migrationType === 'config' || $migrationType === 'all');
        
        // Backup existing files before migration
        echo "Creating backup of existing files before migration..." . PHP_EOL;
        $backupDir = __DIR__ . '/../storage/cms-framework-backup-' . date('Y-m-d-His');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        if ($migrateDatabaseSchema) {
            echo "Backing up database migrations..." . PHP_EOL;
            if (!is_dir($backupDir . '/database')) {
                mkdir($backupDir . '/database', 0755, true);
            }
            if (is_dir(__DIR__ . '/../database/migrations')) {
                $migrations = glob(__DIR__ . '/../database/migrations/*_*_cms_*.php');
                foreach ($migrations as $migration) {
                    copy($migration, $backupDir . '/database/' . basename($migration));
                }
            }
        }
        
        if ($migrateViews) {
            echo "Backing up view templates..." . PHP_EOL;
            if (!is_dir($backupDir . '/views')) {
                mkdir($backupDir . '/views', 0755, true);
            }
            if (is_dir(__DIR__ . '/../resources/views')) {
                // Copy CMS-related views
                $cmsViews = array_merge(
                    glob(__DIR__ . '/../resources/views/cms-*.blade.php'),
                    glob(__DIR__ . '/../resources/views/livewire/cms-*.blade.php')
                );
                foreach ($cmsViews as $view) {
                    $relativePath = str_replace(__DIR__ . '/../resources/views/', '', $view);
                    $backupPath = $backupDir . '/views/' . $relativePath;
                    $backupDir = dirname($backupPath);
                    if (!is_dir($backupDir)) {
                        mkdir($backupDir, 0755, true);
                    }
                    copy($view, $backupPath);
                }
            }
        }
        
        if ($migrateConfig) {
            echo "Backing up configuration files..." . PHP_EOL;
            if (!is_dir($backupDir . '/config')) {
                mkdir($backupDir . '/config', 0755, true);
            }
            if (file_exists(__DIR__ . '/../config/cms-framework.php')) {
                copy(__DIR__ . '/../config/cms-framework.php', $backupDir . '/config/cms-framework.php');
            }
        }
        
        echo "Backup completed. Files saved to: $backupDir" . PHP_EOL;
        
        // Run migration tools
        if ($migrateDatabaseSchema) {
            echo "Running database schema migration..." . PHP_EOL;
            runCommand('php artisan cms-framework:migrate-schema', 'Migrating CMS Framework database schema...');
        }
        
        if ($migrateViews) {
            echo "Running view templates migration..." . PHP_EOL;
            runCommand('php artisan cms-framework:migrate-views', 'Migrating CMS Framework view templates...');
        }
        
        if ($migrateConfig) {
            echo "Running configuration files migration..." . PHP_EOL;
            runCommand('php artisan cms-framework:migrate-config', 'Migrating CMS Framework configuration files...');
        }
        
        echo "Migration completed. Please check the logs for any errors." . PHP_EOL;
    }
} else {
    $installCmsFramework = Prompts::confirm(
        label: 'Would you like to install the artisanpack-ui/cms-framework package?',
        default: false,
        hint: 'Significant changes: Replaces User model and requires database adjustments.'
    );
    $upgradeCmsFramework = false;
    $removeCmsFramework = false;
    $migrateCmsFramework = false;
}

// Prompt for custom installation paths
$useCustomPaths = Prompts::confirm(
    label: 'Would you like to customize installation paths?',
    default: false,
    hint: 'This allows you to specify custom paths for key directories.'
);

$basePath = __DIR__ . '/..';
$customPaths = [];

if ($useCustomPaths) {
    echo PHP_EOL . "Custom Path Configuration" . PHP_EOL;
    echo "------------------------" . PHP_EOL;
    echo "Specify custom paths for key directories. Leave blank to use defaults." . PHP_EOL;
    echo "Paths can be absolute or relative to the project root." . PHP_EOL;
    
    $paths = [
        'views' => [
            'default' => $basePath . '/resources/views',
            'description' => 'Views directory (contains Blade templates)'
        ],
        'assets' => [
            'default' => $basePath . '/resources/assets',
            'description' => 'Assets directory (contains CSS, JS, images)'
        ],
        'config' => [
            'default' => $basePath . '/config',
            'description' => 'Configuration directory'
        ],
        'storage' => [
            'default' => $basePath . '/storage',
            'description' => 'Storage directory (logs, cache, etc.)'
        ]
    ];
    
    foreach ($paths as $key => $info) {
        $customPath = Prompts::text(
            label: $info['description'],
            default: $info['default'],
            hint: "Default: {$info['default']}",
            validate: function ($value) use ($basePath) {
                if (empty($value)) {
                    return null; // Use default
                }
                
                // Convert relative path to absolute
                if (!str_starts_with($value, '/')) {
                    $value = $basePath . '/' . $value;
                }
                
                // Check if path is valid
                if (!is_dir($value) && !mkdir($value, 0755, true)) {
                    return "Unable to create directory: $value";
                }
                
                return null; // Valid
            }
        );
        
        if (!empty($customPath) && $customPath !== $info['default']) {
            $customPaths[$key] = $customPath;
            echo "Using custom path for $key: $customPath" . PHP_EOL;
            
            // Create directory if it doesn't exist
            if (!is_dir($customPath)) {
                if (mkdir($customPath, 0755, true)) {
                    echo "Created directory: $customPath" . PHP_EOL;
                } else {
                    echo "Warning: Failed to create directory: $customPath" . PHP_EOL;
                }
            }
        }
    }
}

// Prompt for confirmation before proceeding with installation
$confirmInstallation = Prompts::confirm(
    label: 'Ready to proceed with installation? This will install selected packages and set up your project.',
    default: true,
    hint: 'You can cancel now if you need to change your selections.'
);

if (!$confirmInstallation) {
    echo "Installation cancelled. You can run the script again when you're ready." . PHP_EOL;
    exit(0);
}

// Install optional packages if selected
if ($installCodeStyle) {
    // Use fallback with --prefer-dist if normal installation fails
    runCommand(
        'composer require artisanpack-ui/code-style --no-interaction', 
        'Installing Code Style package...', 
        'composer require artisanpack-ui/code-style --no-interaction --prefer-dist'
    );
}

if ($installIcons) {
    runCommand(
        'composer require artisanpack-ui/icons --no-interaction', 
        'Installing Icons package...', 
        'composer require artisanpack-ui/icons --no-interaction --prefer-dist'
    );
}

if ($installCmsFramework) {
    // CMS Framework is critical for the application structure, so mark as critical operation
    $success = runCommand(
        'composer require artisanpack-ui/cms-framework --no-interaction', 
        'Installing CMS Framework package...', 
        'composer require artisanpack-ui/cms-framework --no-interaction --prefer-dist',
        true // Critical operation
    );

    // Only proceed with CMS-specific setup if installation was successful
    if ($success) {
        try {
            // Remove User model and migrations as CMS Framework provides its own
            echo "Removing default User model and migrations as CMS Framework provides its own..." . PHP_EOL;
            if (file_exists(__DIR__ . '/../app/Models/User.php')) {
                if (!unlink(__DIR__ . '/../app/Models/User.php')) {
                    echo "Warning: Could not remove User.php. You may need to remove it manually." . PHP_EOL;
                }
            }
            
            // Find and remove the create_users_table migration
            $migrations = glob(__DIR__ . '/../database/migrations/*_create_users_table.php');
            foreach ($migrations as $migration) {
                if (!unlink($migration)) {
                    echo "Warning: Could not remove migration file: $migration. You may need to remove it manually." . PHP_EOL;
                }
            }
        } catch (\Exception $e) {
            echo "Warning: Error during CMS Framework setup: " . $e->getMessage() . PHP_EOL;
            echo "You may need to manually remove the User model and migrations." . PHP_EOL;
        }
    }
    
    // Update auth views to work with CMS Framework's User model
    echo "Updating auth views to work with CMS Framework's User model..." . PHP_EOL;
    copy(__DIR__ . '/../stubs/cms-compatible/register.blade.php', __DIR__ . '/../resources/views/livewire/auth/register.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/login.blade.php', __DIR__ . '/../resources/views/livewire/auth/login.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/forgot-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/forgot-password.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/reset-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/reset-password.blade.php');
    copy(__DIR__ . '/../stubs/cms-compatible/profile.blade.php', __DIR__ . '/../resources/views/livewire/settings/profile.blade.php');
} else {
    // Copy standard versions of files
    echo "Setting up standard User model and auth views..." . PHP_EOL;
    copy(__DIR__ . '/../stubs/standard/User.php', __DIR__ . '/../app/Models/User.php');
    
    // Create a timestamp for the migration file
    $timestamp = date('Y_m_d_His');
    copy(__DIR__ . '/../stubs/standard/create_users_table.php', __DIR__ . "/../database/migrations/{$timestamp}_create_users_table.php");
    
    copy(__DIR__ . '/../stubs/standard/register.blade.php', __DIR__ . '/../resources/views/livewire/auth/register.blade.php');
    copy(__DIR__ . '/../stubs/standard/login.blade.php', __DIR__ . '/../resources/views/livewire/auth/login.blade.php');
    copy(__DIR__ . '/../stubs/standard/forgot-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/forgot-password.blade.php');
    copy(__DIR__ . '/../stubs/standard/reset-password.blade.php', __DIR__ . '/../resources/views/livewire/auth/reset-password.blade.php');
    copy(__DIR__ . '/../stubs/standard/profile.blade.php', __DIR__ . '/../resources/views/livewire/settings/profile.blade.php');
}

// Define color scheme presets
$colorPresets = [
    'default' => [
        'name' => 'Default (Indigo/Gray/Emerald)',
        'light' => [
            'primary' => '#4f46e5',    // Indigo
            'secondary' => '#6b7280',  // Gray
            'accent' => '#10b981'      // Emerald
        ],
        'dark' => [
            'primary' => '#6366f1',    // Lighter Indigo
            'secondary' => '#9ca3af',  // Lighter Gray
            'accent' => '#34d399'      // Lighter Emerald
        ]
    ],
    'blue' => [
        'name' => 'Blue/Slate/Cyan',
        'light' => [
            'primary' => '#2563eb',    // Blue
            'secondary' => '#64748b',  // Slate
            'accent' => '#06b6d4'      // Cyan
        ],
        'dark' => [
            'primary' => '#3b82f6',    // Lighter Blue
            'secondary' => '#94a3b8',  // Lighter Slate
            'accent' => '#22d3ee'      // Lighter Cyan
        ]
    ],
    'purple' => [
        'name' => 'Purple/Zinc/Pink',
        'light' => [
            'primary' => '#9333ea',    // Purple
            'secondary' => '#71717a',  // Zinc
            'accent' => '#ec4899'      // Pink
        ],
        'dark' => [
            'primary' => '#a855f7',    // Lighter Purple
            'secondary' => '#a1a1aa',  // Lighter Zinc
            'accent' => '#f472b6'      // Lighter Pink
        ]
    ],
    'amber' => [
        'name' => 'Amber/Stone/Red',
        'light' => [
            'primary' => '#d97706',    // Amber
            'secondary' => '#78716c',  // Stone
            'accent' => '#dc2626'      // Red
        ],
        'dark' => [
            'primary' => '#f59e0b',    // Lighter Amber
            'secondary' => '#a8a29e',  // Lighter Stone
            'accent' => '#ef4444'      // Lighter Red
        ]
    ],
    'green' => [
        'name' => 'Green/Gray/Blue',
        'light' => [
            'primary' => '#16a34a',    // Green
            'secondary' => '#6b7280',  // Gray
            'accent' => '#2563eb'      // Blue
        ],
        'dark' => [
            'primary' => '#22c55e',    // Lighter Green
            'secondary' => '#9ca3af',  // Lighter Gray
            'accent' => '#3b82f6'      // Lighter Blue
        ]
    ]
];

// Function to generate a color palette based on primary color
function generateColorPalette($primaryColor) {
    // Convert hex to RGB
    $hex = ltrim($primaryColor, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Generate complementary color (opposite on color wheel)
    $complementary = sprintf('#%02x%02x%02x', 255 - $r, 255 - $g, 255 - $b);
    
    // Generate analogous colors (adjacent on color wheel)
    // Simple implementation - adjust hue by +/- 30 degrees
    list($h, $s, $l) = rgbToHsl($r, $g, $b);
    $analogous1 = hslToHex(($h + 30) % 360, $s, $l);
    $analogous2 = hslToHex(($h + 330) % 360, $s, $l);
    
    // Generate monochromatic colors (same hue, different saturation/lightness)
    $mono1 = hslToHex($h, $s, max(0, $l - 0.2));
    $mono2 = hslToHex($h, $s, min(1, $l + 0.2));
    
    // Generate a darker version for dark mode
    $darkMode = hslToHex($h, $s, min(1, $l + 0.15));
    
    return [
        'primary' => $primaryColor,
        'complementary' => $complementary,
        'analogous1' => $analogous1,
        'analogous2' => $analogous2,
        'mono1' => $mono1,
        'mono2' => $mono2,
        'darkMode' => $darkMode
    ];
}

// Helper functions for color conversions
function rgbToHsl($r, $g, $b) {
    $r /= 255;
    $g /= 255;
    $b /= 255;
    
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $l = ($max + $min) / 2;
    
    if ($max == $min) {
        $h = $s = 0; // achromatic
    } else {
        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
        
        switch ($max) {
            case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
            case $g: $h = ($b - $r) / $d + 2; break;
            case $b: $h = ($r - $g) / $d + 4; break;
        }
        
        $h = round($h * 60);
    }
    
    return [$h, $s, $l];
}

function hslToHex($h, $s, $l) {
    $h /= 360;
    
    if ($s == 0) {
        $r = $g = $b = $l; // achromatic
    } else {
        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;
        $r = hueToRgb($p, $q, $h + 1/3);
        $g = hueToRgb($p, $q, $h);
        $b = hueToRgb($p, $q, $h - 1/3);
    }
    
    return sprintf('#%02x%02x%02x', round($r * 255), round($g * 255), round($b * 255));
}

function hueToRgb($p, $q, $t) {
    if ($t < 0) $t += 1;
    if ($t > 1) $t -= 1;
    if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
    if ($t < 1/2) return $q;
    if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
    return $p;
}

// Prompt for UI colors with enhanced options
echo PHP_EOL . "UI Color Customization" . PHP_EOL;
echo "---------------------" . PHP_EOL;
echo "You can choose a preset color scheme or customize your own colors." . PHP_EOL;

// Ask if user wants to use a preset
$usePreset = Prompts::confirm(
    label: 'Would you like to use a preset color scheme?',
    default: true,
    hint: 'Presets provide harmonious color combinations for both light and dark modes.'
);

$primaryColor = '';
$secondaryColor = '';
$accentColor = '';
$darkModePrimary = '';
$darkModeSecondary = '';
$darkModeAccent = '';
$enableDarkMode = false;

if ($usePreset) {
    // List available presets
    $presetOptions = [];
    foreach ($colorPresets as $key => $preset) {
        $presetOptions[$key] = $preset['name'];
    }
    
    $selectedPreset = Prompts::select(
        label: 'Select a color scheme preset',
        options: $presetOptions,
        default: 'default',
        hint: 'Each preset includes coordinated colors for light and dark modes.'
    );
    
    // Set colors from preset
    $primaryColor = $colorPresets[$selectedPreset]['light']['primary'];
    $secondaryColor = $colorPresets[$selectedPreset]['light']['secondary'];
    $accentColor = $colorPresets[$selectedPreset]['light']['accent'];
    $darkModePrimary = $colorPresets[$selectedPreset]['dark']['primary'];
    $darkModeSecondary = $colorPresets[$selectedPreset]['dark']['secondary'];
    $darkModeAccent = $colorPresets[$selectedPreset]['dark']['accent'];
    
    echo "Selected preset: {$colorPresets[$selectedPreset]['name']}" . PHP_EOL;
    echo "Light mode colors: Primary: $primaryColor, Secondary: $secondaryColor, Accent: $accentColor" . PHP_EOL;
    echo "Dark mode colors: Primary: $darkModePrimary, Secondary: $darkModeSecondary, Accent: $darkModeAccent" . PHP_EOL;
} else {
    // Custom color selection
    echo "Enter custom colors in hex format (e.g., #4f46e5):" . PHP_EOL;
    
    $primaryColor = Prompts::text(
        label: 'Enter your primary color (hex code)',
        default: '#4f46e5',
        validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code (e.g. #4f46e5)' : null
    );
    
    // Generate a color palette based on primary color
    $palette = generateColorPalette($primaryColor);
    
    echo "Based on your primary color, we suggest:" . PHP_EOL;
    echo "- Secondary color: {$palette['analogous1']}" . PHP_EOL;
    echo "- Accent color: {$palette['complementary']}" . PHP_EOL;
    
    $secondaryColor = Prompts::text(
        label: 'Enter your secondary color (hex code)',
        default: $palette['analogous1'],
        validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code (e.g. #6b7280)' : null
    );
    
    $accentColor = Prompts::text(
        label: 'Enter your accent color (hex code)',
        default: $palette['complementary'],
        validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code (e.g. #10b981)' : null
    );
    
    // Ask about dark mode
    $enableDarkMode = Prompts::confirm(
        label: 'Would you like to enable dark mode support?',
        default: true,
        hint: 'This will generate a separate dark color scheme based on your selections.'
    );
    
    if ($enableDarkMode) {
        echo "Suggested dark mode colors (lighter versions of your selections):" . PHP_EOL;
        echo "- Primary: {$palette['darkMode']}" . PHP_EOL;
        
        $darkModePrimary = Prompts::text(
            label: 'Enter dark mode primary color (hex code)',
            default: $palette['darkMode'],
            validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code' : null
        );
        
        // Generate dark mode secondary and accent based on the selected dark mode primary
        $darkPalette = generateColorPalette($darkModePrimary);
        
        $darkModeSecondary = Prompts::text(
            label: 'Enter dark mode secondary color (hex code)',
            default: $darkPalette['analogous1'],
            validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code' : null
        );
        
        $darkModeAccent = Prompts::text(
            label: 'Enter dark mode accent color (hex code)',
            default: $darkPalette['complementary'],
            validate: fn ($value) => !preg_match('/^#[a-f0-9]{6}$/i', $value) ? 'Please enter a valid hex color code' : null
        );
    }
}

// Typography Customization
echo PHP_EOL . "Typography Customization" . PHP_EOL;
echo "---------------------" . PHP_EOL;
echo "Customize the typography for your application." . PHP_EOL;

// Font Family Selection
$fontFamilyOptions = [
    'system' => 'System UI (Default)',
    'inter' => 'Inter',
    'roboto' => 'Roboto',
    'open-sans' => 'Open Sans',
    'montserrat' => 'Montserrat',
    'lato' => 'Lato',
    'poppins' => 'Poppins',
    'custom' => 'Custom Font'
];

$selectedFontFamily = Prompts::select(
    label: 'Select a font family',
    options: $fontFamilyOptions,
    default: 'system',
    hint: 'The primary font family for your application.'
);

$customFontFamily = '';
$customFontUrl = '';

if ($selectedFontFamily === 'custom') {
    $customFontFamily = Prompts::text(
        label: 'Enter custom font family name',
        default: 'CustomFont',
        validate: fn ($value) => empty($value) ? 'Font family name cannot be empty' : null
    );
    
    $customFontUrl = Prompts::text(
        label: 'Enter custom font URL (CDN or local path)',
        default: 'https://fonts.googleapis.com/css2?family=CustomFont:wght@400;500;600;700&display=swap',
        validate: fn ($value) => empty($value) ? 'Font URL cannot be empty' : null
    );
}

// Font Size Scaling
$fontSizeOptions = [
    'small' => 'Small (0.875x)',
    'default' => 'Default (1x)',
    'large' => 'Large (1.125x)',
    'x-large' => 'Extra Large (1.25x)',
    'custom' => 'Custom Scale Factor'
];

$selectedFontSize = Prompts::select(
    label: 'Select a font size scale',
    options: $fontSizeOptions,
    default: 'default',
    hint: 'The overall scaling factor for font sizes.'
);

$customScaleFactor = 1.0;

if ($selectedFontSize === 'custom') {
    $customScaleFactor = Prompts::text(
        label: 'Enter custom scale factor',
        default: '1.0',
        validate: function ($value) {
            if (!is_numeric($value) || $value <= 0) {
                return 'Scale factor must be a positive number';
            }
            return null;
        }
    );
    $customScaleFactor = (float) $customScaleFactor;
} else {
    // Set scale factor based on selection
    switch ($selectedFontSize) {
        case 'small':
            $customScaleFactor = 0.875;
            break;
        case 'large':
            $customScaleFactor = 1.125;
            break;
        case 'x-large':
            $customScaleFactor = 1.25;
            break;
        default:
            $customScaleFactor = 1.0;
    }
}

// Generate CSS with the selected colors and typography
echo "Generating CSS with your customizations..." . PHP_EOL;

$cssParams = "--primary={$primaryColor} --secondary={$secondaryColor} --accent={$accentColor}";

if ($enableDarkMode || $usePreset) {
    $cssParams .= " --dark-primary={$darkModePrimary} --dark-secondary={$darkModeSecondary} --dark-accent={$darkModeAccent} --enable-dark-mode=true";
}

$cssParams .= " --font-family={$selectedFontFamily}";
if ($selectedFontFamily === 'custom') {
    $cssParams .= " --custom-font-family=\"{$customFontFamily}\" --custom-font-url=\"{$customFontUrl}\"";
}

$cssParams .= " --font-scale={$customScaleFactor}";

$cssCommand = "php artisan generate:css {$cssParams}";
runCommand($cssCommand, 'Generating custom CSS with your color and typography choices...');

// Copy theme configuration file
echo "Setting up theme configuration..." . PHP_EOL;
if (!is_dir(__DIR__ . '/../config')) {
    mkdir(__DIR__ . '/../config', 0755, true);
}
copy(__DIR__ . '/../stubs/standard/config/theme.php', __DIR__ . '/../config/theme.php');

// Update theme configuration with selected values
$themeConfig = file_get_contents(__DIR__ . '/../config/theme.php');
$themeConfig = str_replace("'primary' => '#4f46e5',", "'primary' => '{$primaryColor}',", $themeConfig);
$themeConfig = str_replace("'secondary' => '#6b7280',", "'secondary' => '{$secondaryColor}',", $themeConfig);
$themeConfig = str_replace("'accent' => '#10b981',", "'accent' => '{$accentColor}',", $themeConfig);

if ($enableDarkMode || $usePreset) {
    $themeConfig = str_replace("'primary' => '#6366f1',", "'primary' => '{$darkModePrimary}',", $themeConfig);
    $themeConfig = str_replace("'secondary' => '#9ca3af',", "'secondary' => '{$darkModeSecondary}',", $themeConfig);
    $themeConfig = str_replace("'accent' => '#34d399',", "'accent' => '{$darkModeAccent}',", $themeConfig);
}

// Update font family in theme config
if ($selectedFontFamily === 'custom') {
    $fontFamilyString = "'{$customFontFamily}, sans-serif'";
} else {
    switch ($selectedFontFamily) {
        case 'inter':
            $fontFamilyString = "'Inter, sans-serif'";
            break;
        case 'roboto':
            $fontFamilyString = "'Roboto, sans-serif'";
            break;
        case 'open-sans':
            $fontFamilyString = "'Open Sans, sans-serif'";
            break;
        case 'montserrat':
            $fontFamilyString = "'Montserrat, sans-serif'";
            break;
        case 'lato':
            $fontFamilyString = "'Lato, sans-serif'";
            break;
        case 'poppins':
            $fontFamilyString = "'Poppins, sans-serif'";
            break;
        default:
            $fontFamilyString = "'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif'";
    }
}

$themeConfig = preg_replace(
    "/'sans' => '.*?'/",
    "'sans' => {$fontFamilyString}",
    $themeConfig
);

// Update font scale in theme config
$themeConfig = str_replace(
    "'scale' => 'default',",
    "'scale' => '{$selectedFontSize}',",
    $themeConfig
);
$themeConfig = str_replace(
    "'custom_scale_factor' => 1.0,",
    "'custom_scale_factor' => {$customScaleFactor},",
    $themeConfig
);

// Add custom font if specified
if ($selectedFontFamily === 'custom') {
    $themeConfig = str_replace(
        "// 'font_name' => 'url_to_font',",
        "'{$customFontFamily}' => '{$customFontUrl}',",
        $themeConfig
    );
}

file_put_contents(__DIR__ . '/../config/theme.php', $themeConfig);

// Copy ThemeSwitcher component
if (!is_dir(__DIR__ . '/../app/Livewire')) {
    mkdir(__DIR__ . '/../app/Livewire', 0755, true);
}
copy(__DIR__ . '/../stubs/standard/app/Livewire/ThemeSwitcher.php', __DIR__ . '/../app/Livewire/ThemeSwitcher.php');

// Create views directory if it doesn't exist
if (!is_dir(__DIR__ . '/../resources/views/livewire')) {
    mkdir(__DIR__ . '/../resources/views/livewire', 0755, true);
}
copy(__DIR__ . '/../stubs/standard/resources/views/livewire/theme-switcher.blade.php', __DIR__ . '/../resources/views/livewire/theme-switcher.blade.php');

// Finalize installation
echo "🎉 Your ArtisanPack UI Laravel Starter Kit is almost ready!" . PHP_EOL;

// Clean up installation script
$removeScript = Prompts::confirm(
    label: 'Would you like to remove the installation script?',
    default: true
);

if ($removeScript) {
    unlink(__FILE__);
    echo "Installation script removed." . PHP_EOL;
}

echo "✅ Setup complete! Run 'php artisan serve' to start your application." . PHP_EOL;