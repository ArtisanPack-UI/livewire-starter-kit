<div>
    <x-artisanpack-text tag="h2" class="text-lg font-medium text-gray-900">
        Profile Information
    </x-artisanpack-text>

    <p class="mt-1 text-sm text-gray-600">
        Update your account's profile information and email address.
    </p>

    <form wire:submit="save" class="mt-6 space-y-6">
        <div>
            <x-artisanpack-input 
                wire:model="username" 
                id="username" 
                name="username" 
                type="text" 
                label="Username" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-artisanpack-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-artisanpack-input 
                wire:model="first_name" 
                id="first_name" 
                name="first_name" 
                type="text" 
                label="First Name" 
                required 
                autocomplete="given-name" 
            />
            <x-artisanpack-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-artisanpack-input 
                wire:model="last_name" 
                id="last_name" 
                name="last_name" 
                type="text" 
                label="Last Name" 
                required 
                autocomplete="family-name" 
            />
            <x-artisanpack-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-artisanpack-input 
                wire:model="email" 
                id="email" 
                name="email" 
                type="email" 
                label="Email" 
                required 
                autocomplete="email" 
            />
            <x-artisanpack-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Your email address is unverified.

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-artisanpack-button>
                Save
            </x-artisanpack-button>

            <x-artisanpack-action-message class="me-3" on="profile-updated">
                Saved.
            </x-artisanpack-action-message>
        </div>
    </form>
</div>