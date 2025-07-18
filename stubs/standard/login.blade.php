<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <x-artisanpack-text tag="h2" class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900">
            Sign in to your account
        </x-artisanpack-text>

        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">
                create a new account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form wire:submit="login" class="space-y-6">
                <div>
                    <x-artisanpack-input 
                        wire:model="email" 
                        id="email" 
                        name="email" 
                        type="email" 
                        label="Email address" 
                        required 
                        autofocus 
                        autocomplete="username" 
                    />
                    <x-artisanpack-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-artisanpack-input 
                        wire:model="password" 
                        id="password" 
                        name="password" 
                        type="password" 
                        label="Password" 
                        required 
                        autocomplete="current-password" 
                    />
                    <x-artisanpack-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <x-artisanpack-checkbox 
                            wire:model="remember" 
                            id="remember" 
                            name="remember" 
                        />
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <x-artisanpack-button type="submit" class="w-full">
                        Sign in
                    </x-artisanpack-button>
                </div>
            </form>
        </div>
    </div>
</div>