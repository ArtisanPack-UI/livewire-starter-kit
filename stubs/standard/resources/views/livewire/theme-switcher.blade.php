<div>
    <div class="theme-switcher">
        <!-- Theme Toggle Button -->
        <button 
            type="button" 
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" 
            wire:click="toggleTheme" 
            aria-label="Toggle theme"
        >
            @if($currentTheme === 'dark')
                <!-- Sun icon for dark mode (clicking switches to light) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                </svg>
            @else
                <!-- Moon icon for light mode (clicking switches to dark) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                </svg>
            @endif
        </button>

        <!-- Dropdown for more theme options -->
        <div x-data="{ open: false }" class="relative inline-block text-left ml-2">
            <button 
                type="button" 
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                x-on:click="open = !open"
                aria-haspopup="true" 
                aria-expanded="false"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                </svg>
            </button>

            <div 
                x-show="open" 
                x-on:click.away="open = false"
                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                role="menu" 
                aria-orientation="vertical"
            >
                <div class="py-1" role="none">
                    <!-- Light Theme Option -->
                    <button 
                        wire:click="switchTheme('light')" 
                        x-on:click="open = false"
                        class="w-full text-left px-4 py-2 text-sm {{ $currentTheme === 'light' ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-700"
                        role="menuitem"
                    >
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                            Light
                        </div>
                    </button>

                    <!-- Dark Theme Option -->
                    <button 
                        wire:click="switchTheme('dark')" 
                        x-on:click="open = false"
                        class="w-full text-left px-4 py-2 text-sm {{ $currentTheme === 'dark' ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-700"
                        role="menuitem"
                    >
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            Dark
                        </div>
                    </button>

                    <!-- System Theme Option -->
                    <button 
                        wire:click="detectSystemPreference" 
                        x-on:click="open = false"
                        class="w-full text-left px-4 py-2 text-sm {{ $currentTheme === 'system' ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-700"
                        role="menuitem"
                    >
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                            </svg>
                            System
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for system preference detection -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if the user has selected 'system' theme
            const currentTheme = '{{ $currentTheme }}';
            if (currentTheme === 'system') {
                applySystemTheme();
            }

            // Listen for theme changes
            window.addEventListener('theme-changed', event => {
                if (event.detail.theme === 'system') {
                    applySystemTheme();
                }
            });

            // Apply theme based on system preference
            function applySystemTheme() {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }

                // Listen for changes in system preference
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                    if (currentTheme === 'system') {
                        if (e.matches) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                });
            }
        });
    </script>

    <!-- No custom CSS needed, using Tailwind classes instead -->
</div>