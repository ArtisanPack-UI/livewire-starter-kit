<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <x-artisanpack-text tag="h2" class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
            Create a new account
        </x-artisanpack-text>

        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            Or
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                sign in to your account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form wire:submit="register" class="space-y-6">
                <!-- Basic Information Section -->
                <div class="space-y-4">
                    <x-artisanpack-text tag="h3" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Basic Information
                    </x-artisanpack-text>
                    
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
                        <x-artisanpack-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            <x-artisanpack-input-error :messages="$errors->get('first_name')" class="mt-2" />
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
                            <x-artisanpack-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-artisanpack-input 
                            wire:model="email" 
                            id="email" 
                            name="email" 
                            type="email" 
                            label="Email address" 
                            required 
                            autocomplete="email" 
                        />
                        <x-artisanpack-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <!-- Additional Information Section (Optional) -->
                @if(config('cms-framework.registration.collect_additional_info', false))
                <div class="space-y-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-artisanpack-text tag="h3" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Additional Information
                    </x-artisanpack-text>
                    
                    @if(config('cms-framework.registration.fields.phone', false))
                    <div>
                        <x-artisanpack-input 
                            wire:model="phone" 
                            id="phone" 
                            name="phone" 
                            type="tel" 
                            label="Phone Number" 
                            autocomplete="tel" 
                            :required="config('cms-framework.registration.fields.phone_required', false)"
                        />
                        <x-artisanpack-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    @endif

                    @if(config('cms-framework.registration.fields.address', false))
                    <div class="space-y-4">
                        <div>
                            <x-artisanpack-input 
                                wire:model="address_line1" 
                                id="address_line1" 
                                name="address_line1" 
                                type="text" 
                                label="Address Line 1" 
                                autocomplete="address-line1" 
                                :required="config('cms-framework.registration.fields.address_required', false)"
                            />
                            <x-artisanpack-input-error :messages="$errors->get('address_line1')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-artisanpack-input 
                                wire:model="address_line2" 
                                id="address_line2" 
                                name="address_line2" 
                                type="text" 
                                label="Address Line 2 (Optional)" 
                                autocomplete="address-line2" 
                            />
                            <x-artisanpack-input-error :messages="$errors->get('address_line2')" class="mt-2" />
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-artisanpack-input 
                                    wire:model="city" 
                                    id="city" 
                                    name="city" 
                                    type="text" 
                                    label="City" 
                                    autocomplete="address-level2" 
                                    :required="config('cms-framework.registration.fields.address_required', false)"
                                />
                                <x-artisanpack-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-artisanpack-input 
                                    wire:model="state" 
                                    id="state" 
                                    name="state" 
                                    type="text" 
                                    label="State/Province" 
                                    autocomplete="address-level1" 
                                    :required="config('cms-framework.registration.fields.address_required', false)"
                                />
                                <x-artisanpack-input-error :messages="$errors->get('state')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-artisanpack-input 
                                    wire:model="postal_code" 
                                    id="postal_code" 
                                    name="postal_code" 
                                    type="text" 
                                    label="Postal Code" 
                                    autocomplete="postal-code" 
                                    :required="config('cms-framework.registration.fields.address_required', false)"
                                />
                                <x-artisanpack-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-artisanpack-input 
                                    wire:model="country" 
                                    id="country" 
                                    name="country" 
                                    type="text" 
                                    label="Country" 
                                    autocomplete="country-name" 
                                    :required="config('cms-framework.registration.fields.address_required', false)"
                                />
                                <x-artisanpack-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(config('cms-framework.registration.fields.bio', false))
                    <div>
                        <x-artisanpack-textarea 
                            wire:model="bio" 
                            id="bio" 
                            name="bio" 
                            label="Bio" 
                            :required="config('cms-framework.registration.fields.bio_required', false)"
                            rows="3"
                            placeholder="Tell us a little about yourself..."
                        />
                        <x-artisanpack-input-error :messages="$errors->get('bio')" class="mt-2" />
                    </div>
                    @endif

                    @if(config('cms-framework.registration.fields.profile_image', false))
                    <div>
                        <x-artisanpack-file-upload 
                            wire:model="profile_image" 
                            id="profile_image" 
                            name="profile_image" 
                            label="Profile Image" 
                            :required="config('cms-framework.registration.fields.profile_image_required', false)"
                            accept="image/*"
                            :max-size="config('cms-framework.registration.fields.profile_image_max_size', 2048)"
                        />
                        <x-artisanpack-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                    </div>
                    @endif
                </div>
                @endif

                <!-- Password Section -->
                <div class="space-y-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-artisanpack-text tag="h3" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Security
                    </x-artisanpack-text>
                    
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
                        
                        @if(config('cms-framework.registration.show_password_requirements', true))
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Password must be at least 8 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.
                        </div>
                        @endif
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
                </div>

                <!-- Terms and Conditions -->
                @if(config('cms-framework.registration.require_terms_acceptance', true))
                <div class="pt-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <x-artisanpack-checkbox 
                                wire:model="terms_accepted" 
                                id="terms_accepted" 
                                name="terms_accepted" 
                                required
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms_accepted" class="font-medium text-gray-700 dark:text-gray-300">
                                I agree to the 
                                <a href="{{ route('terms') }}" target="_blank" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                                    Terms of Service
                                </a> 
                                and 
                                <a href="{{ route('privacy') }}" target="_blank" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                                    Privacy Policy
                                </a>
                            </label>
                            <x-artisanpack-input-error :messages="$errors->get('terms_accepted')" class="mt-2" />
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Button -->
                <div class="pt-4">
                    <x-artisanpack-button type="submit" class="w-full">
                        Register
                    </x-artisanpack-button>
                </div>
            </form>
        </div>
    </div>
</div>