<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the application's theme system.
    | It includes settings for light and dark mode, color schemes, typography,
    | and theme switching functionality.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | The default theme to use when a user hasn't set a preference.
    | Options: 'light', 'dark', 'system' (follows system preference)
    |
    */
    'default' => 'light',

    /*
    |--------------------------------------------------------------------------
    | Theme Persistence
    |--------------------------------------------------------------------------
    |
    | How to persist the user's theme preference.
    | Options: 'cookie', 'session', 'database'
    |
    */
    'persistence' => 'cookie',

    /*
    |--------------------------------------------------------------------------
    | Cookie Settings
    |--------------------------------------------------------------------------
    |
    | Settings for the theme cookie when using cookie persistence.
    |
    */
    'cookie' => [
        'name' => 'theme_preference',
        'lifetime' => 60 * 24 * 365, // 1 year in minutes
        'path' => '/',
        'domain' => null,
        'secure' => true,
        'http_only' => true,
        'same_site' => 'lax',
    ],

    /*
    |--------------------------------------------------------------------------
    | Color Schemes
    |--------------------------------------------------------------------------
    |
    | Color schemes for light and dark modes.
    |
    */
    'colors' => [
        'light' => [
            'primary' => '#4f46e5',    // Indigo
            'secondary' => '#6b7280',  // Gray
            'accent' => '#10b981',     // Emerald
        ],
        'dark' => [
            'primary' => '#6366f1',    // Lighter Indigo
            'secondary' => '#9ca3af',  // Lighter Gray
            'accent' => '#34d399',     // Lighter Emerald
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Typography
    |--------------------------------------------------------------------------
    |
    | Typography settings including font families and scaling.
    |
    */
    'typography' => [
        'font_family' => [
            'sans' => 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif',
            'serif' => 'ui-serif, Georgia, Cambria, "Times New Roman", Times, serif',
            'mono' => 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',
        ],
        'font_size' => [
            'scale' => 'default', // Options: 'small', 'default', 'large'
            'custom_scale_factor' => 1.0, // Custom scaling factor
        ],
        'custom_fonts' => [
            // Add custom font imports here
            // 'font_name' => 'url_to_font',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-switching
    |--------------------------------------------------------------------------
    |
    | Settings for automatic theme switching.
    |
    */
    'auto_switch' => [
        'enabled' => true,
        'detect_system_preference' => true,
        'schedule' => [
            'enabled' => false,
            'light_start' => '06:00', // 6 AM
            'dark_start' => '18:00',  // 6 PM
        ],
    ],
];