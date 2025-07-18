<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <x-artisanpack-text tag="h2" class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900">
            Reset Password
        </x-artisanpack-text>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form wire:submit="resetPassword" class="space-y-6">
                <input wire:model="token" type="hidden">

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
                        Reset Password
                    </x-artisanpack-button>
                </div>
            </form>
        </div>
    </div>
</div>