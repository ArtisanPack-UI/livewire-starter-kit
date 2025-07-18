<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <x-artisanpack-text tag="h2" class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900">
            Forgot your password?
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
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-artisanpack-alert-success class="mb-4" :status="session('status')" />

            <form wire:submit="sendPasswordResetLink" class="space-y-6">
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
                    <x-artisanpack-button type="submit" class="w-full">
                        Email Password Reset Link
                    </x-artisanpack-button>
                </div>
            </form>
        </div>
    </div>
</div>