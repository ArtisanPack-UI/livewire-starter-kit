<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;

class ThemeSwitcher extends Component
{
    /**
     * The current theme.
     *
     * @var string
     */
    public $currentTheme;

    /**
     * Available themes.
     *
     * @var array
     */
    public $availableThemes = ['light', 'dark', 'system'];

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->currentTheme = $this->getThemePreference();
    }

    /**
     * Get the user's theme preference.
     *
     * @return string
     */
    protected function getThemePreference()
    {
        $persistenceMethod = config('theme.persistence', 'cookie');
        $defaultTheme = config('theme.default', 'light');

        if ($persistenceMethod === 'cookie') {
            $cookieName = config('theme.cookie.name', 'theme_preference');
            return Cookie::get($cookieName, $defaultTheme);
        } elseif ($persistenceMethod === 'session') {
            return session('theme_preference', $defaultTheme);
        } elseif ($persistenceMethod === 'database' && auth()->check()) {
            // If using database persistence and user is authenticated
            return auth()->user()->theme_preference ?? $defaultTheme;
        }

        return $defaultTheme;
    }

    /**
     * Switch to the specified theme.
     *
     * @param string $theme
     * @return void
     */
    public function switchTheme($theme)
    {
        if (!in_array($theme, $this->availableThemes)) {
            $theme = config('theme.default', 'light');
        }

        $this->currentTheme = $theme;
        $this->persistThemePreference($theme);

        $this->dispatch('theme-changed', ['theme' => $theme]);
    }

    /**
     * Persist the user's theme preference.
     *
     * @param string $theme
     * @return void
     */
    protected function persistThemePreference($theme)
    {
        $persistenceMethod = config('theme.persistence', 'cookie');

        if ($persistenceMethod === 'cookie') {
            $cookieConfig = config('theme.cookie', [
                'name' => 'theme_preference',
                'lifetime' => 60 * 24 * 365, // 1 year in minutes
                'path' => '/',
                'domain' => null,
                'secure' => true,
                'http_only' => true,
                'same_site' => 'lax',
            ]);

            Cookie::queue(
                $cookieConfig['name'],
                $theme,
                $cookieConfig['lifetime'],
                $cookieConfig['path'],
                $cookieConfig['domain'],
                $cookieConfig['secure'],
                $cookieConfig['http_only'],
                false,
                $cookieConfig['same_site']
            );
        } elseif ($persistenceMethod === 'session') {
            session(['theme_preference' => $theme]);
        } elseif ($persistenceMethod === 'database' && auth()->check()) {
            // If using database persistence and user is authenticated
            $user = auth()->user();
            $user->theme_preference = $theme;
            $user->save();
        }
    }

    /**
     * Toggle between light and dark themes.
     *
     * @return void
     */
    public function toggleTheme()
    {
        $newTheme = $this->currentTheme === 'dark' ? 'light' : 'dark';
        $this->switchTheme($newTheme);
    }

    /**
     * Detect system preference for dark mode.
     *
     * @return void
     */
    public function detectSystemPreference()
    {
        // This is handled client-side with JavaScript
        $this->switchTheme('system');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.theme-switcher');
    }
}