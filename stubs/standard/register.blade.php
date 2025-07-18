<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <x-artisanpack-text tag="h2" class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900">
            Create a new account
        </x-artisanpack-text>

        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                sign in to your account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form wire:submit="register" class="space-y-6">
                <div>
                    <x-artisanpack-input 
                        wire:model="name" 
                        id="name" 
                        name="name" 
                        type="text" 
                        label="Name" 
                        required 
                        autofocus 
                        autocomplete="name" 
                    />
                    <x-artisanpack-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-artisanpack-input 
                        wire:model="email" 
                        id="email" 
                        name="email" 
                        type="email" 
                        label="Email address" 
                        required 
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
                        autocomplete="new-password" 
                    />
                    <x-artisanpack-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-artisanpack-input 
                        wire:model="password_confirmation" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        label="Confirm Password" 
                        required 
                        autocomplete="new-password" 
                    />
                    <x-artisanpack-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div>
                    <x-artisanpack-button type="submit" class="w-full">
                        Register
                    </x-artisanpack-button>
                </div>
            </form>
        </div>
    </div>
</div>