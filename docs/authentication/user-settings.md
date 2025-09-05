---
title: User Settings
---

# User Settings

The user settings system allows authenticated users to manage their profile information, password, and account preferences.

## Overview

User settings include:

- **Profile Management** - Name, email, and personal information
- **Password Updates** - Secure password changes
- **Account Preferences** - Theme, notifications, and other settings
- **Account Deletion** - Self-service account deletion
- **Security Settings** - Two-factor authentication and sessions

## Settings Pages

The settings are organized into multiple pages:

- `/settings/profile` - Profile information management
- `/settings/password` - Password change functionality
- `/settings/preferences` - User preferences and theme
- `/settings/security` - Security settings and 2FA
- `/settings/account` - Account deletion and data export

## Profile Management

### Profile Settings Component

The profile settings allow users to update their basic information:

```php
<?php

use App\Models\User;
use Illuminate\Validation\Rule;
use function Livewire\Volt\{state, rules, mount};

state([
    'name' => '',
    'email' => '',
]);

mount(function () {
    $user = auth()->user();
    $this->name = $user->name;
    $this->email = $user->email;
});

rules(function () {
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(auth()->id())],
    ];
});

$updateProfile = function () {
    $this->validate();
    
    $user = auth()->user();
    $emailChanged = $user->email !== $this->email;
    
    $user->update([
        'name' => $this->name,
        'email' => $this->email,
        'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
    ]);
    
    if ($emailChanged) {
        $user->sendEmailVerificationNotification();
        $this->dispatch('profile-updated', ['message' => 'Profile updated. Please verify your new email address.']);
    } else {
        $this->dispatch('profile-updated', ['message' => 'Profile updated successfully.']);
    }
};

?>

<div>
    <form wire:submit="updateProfile">
        <x-artisanpack-input 
            wire:model="name" 
            label="Name" 
            placeholder="Enter your full name"
            required
            :error="$errors->first('name')"
        />
        
        <x-artisanpack-input 
            wire:model="email" 
            label="Email" 
            type="email"
            placeholder="Enter your email address"
            required
            :error="$errors->first('email')"
        />
        
        <x-artisanpack-button type="submit" variant="primary">
            <span wire:loading.remove wire:target="updateProfile">Update Profile</span>
            <span wire:loading wire:target="updateProfile">Updating...</span>
        </x-artisanpack-button>
    </form>
</div>
```

### Profile Form

The profile form includes validation and real-time feedback:

```blade
<form wire:submit="updateProfile" class="space-y-6">
    <div>
        <x-artisanpack-input
            wire:model.live="name"
            label="Full Name"
            placeholder="Enter your full name"
            required
            autocomplete="name"
            :error="$errors->first('name')"
        />
    </div>

    <div>
        <x-artisanpack-input
            wire:model.live="email"
            label="Email Address"
            type="email"
            placeholder="Enter your email address"
            required
            autocomplete="email"
            :error="$errors->first('email')"
        />
        
        @if(auth()->user()->email !== $this->email)
            <x-artisanpack-text size="sm" class="mt-1 text-amber-600">
                Changing your email will require verification.
            </x-artisanpack-text>
        @endif
    </div>

    <div class="flex items-center justify-between">
        <x-artisanpack-button type="submit" variant="primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="updateProfile">Update Profile</span>
            <span wire:loading wire:target="updateProfile">Updating...</span>
        </x-artisanpack-button>
        
        <x-artisanpack-text size="sm" class="text-green-600" wire:loading.remove wire:target="updateProfile">
            @if(session('profile-updated'))
                {{ session('profile-updated') }}
            @endif
        </x-artisanpack-text>
    </div>
</form>
```

## Password Management

### Password Update Component

Secure password updates with current password verification:

```php
<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use function Livewire\Volt\{state, rules};

state([
    'current_password' => '',
    'password' => '',
    'password_confirmation' => '',
]);

rules([
    'current_password' => ['required', 'string'],
    'password' => ['required', 'string', 'confirmed', Password::defaults()],
]);

$updatePassword = function () {
    $this->validate();
    
    $user = auth()->user();
    
    if (!Hash::check($this->current_password, $user->password)) {
        throw ValidationException::withMessages([
            'current_password' => 'The current password is incorrect.',
        ]);
    }
    
    $user->update([
        'password' => Hash::make($this->password),
    ]);
    
    // Invalidate other sessions
    auth()->logoutOtherDevices($this->password);
    
    $this->reset(['current_password', 'password', 'password_confirmation']);
    
    $this->dispatch('password-updated', ['message' => 'Password updated successfully.']);
};

?>

<div>
    <form wire:submit="updatePassword">
        <x-artisanpack-input
            wire:model="current_password"
            label="Current Password"
            type="password"
            placeholder="Enter your current password"
            required
            autocomplete="current-password"
            :error="$errors->first('current_password')"
        />
        
        <x-artisanpack-input
            wire:model="password"
            label="New Password"
            type="password"
            placeholder="Enter a new password"
            required
            autocomplete="new-password"
            :error="$errors->first('password')"
        />
        
        <x-artisanpack-input
            wire:model="password_confirmation"
            label="Confirm Password"
            type="password"
            placeholder="Confirm your new password"
            required
            autocomplete="new-password"
        />
        
        <x-artisanpack-button type="submit" variant="primary">
            <span wire:loading.remove wire:target="updatePassword">Update Password</span>
            <span wire:loading wire:target="updatePassword">Updating...</span>
        </x-artisanpack-button>
    </form>
</div>
```

### Password Requirements

Display password requirements to users:

```blade
<div class="mt-2">
    <x-artisanpack-text size="sm" class="font-medium">Password Requirements:</x-artisanpack-text>
    <ul class="mt-1 space-y-1 text-sm text-gray-600">
        <li class="flex items-center">
            <x-artisanpack-icon name="check" class="w-4 h-4 mr-2 text-green-500" />
            At least 8 characters
        </li>
        <li class="flex items-center">
            <x-artisanpack-icon name="check" class="w-4 h-4 mr-2 text-green-500" />
            Include uppercase and lowercase letters
        </li>
        <li class="flex items-center">
            <x-artisanpack-icon name="check" class="w-4 h-4 mr-2 text-green-500" />
            Include at least one number
        </li>
        <li class="flex items-center">
            <x-artisanpack-icon name="check" class="w-4 h-4 mr-2 text-green-500" />
            Include at least one special character
        </li>
    </ul>
</div>
```

## User Preferences

### Theme Preferences

Allow users to choose their preferred theme:

```php
<?php

use function Livewire\Volt\{state, mount};

state([
    'theme' => 'system',
    'notifications_enabled' => true,
    'email_notifications' => true,
]);

mount(function () {
    $user = auth()->user();
    $this->theme = $user->preferences['theme'] ?? 'system';
    $this->notifications_enabled = $user->preferences['notifications_enabled'] ?? true;
    $this->email_notifications = $user->preferences['email_notifications'] ?? true;
});

$updatePreferences = function () {
    $user = auth()->user();
    
    $preferences = [
        'theme' => $this->theme,
        'notifications_enabled' => $this->notifications_enabled,
        'email_notifications' => $this->email_notifications,
    ];
    
    $user->update(['preferences' => $preferences]);
    
    $this->dispatch('preferences-updated', ['message' => 'Preferences updated successfully.']);
};

?>

<div>
    <form wire:submit="updatePreferences">
        <div class="space-y-6">
            <div>
                <x-artisanpack-text class="font-medium">Theme</x-artisanpack-text>
                <div class="mt-2 space-y-2">
                    <x-artisanpack-radio wire:model.live="theme" value="light" label="Light" />
                    <x-artisanpack-radio wire:model.live="theme" value="dark" label="Dark" />
                    <x-artisanpack-radio wire:model.live="theme" value="system" label="System" />
                </div>
            </div>
            
            <div>
                <x-artisanpack-text class="font-medium">Notifications</x-artisanpack-text>
                <div class="mt-2 space-y-2">
                    <x-artisanpack-checkbox wire:model="notifications_enabled" label="Enable push notifications" />
                    <x-artisanpack-checkbox wire:model="email_notifications" label="Enable email notifications" />
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <x-artisanpack-button type="submit" variant="primary">
                <span wire:loading.remove wire:target="updatePreferences">Save Preferences</span>
                <span wire:loading wire:target="updatePreferences">Saving...</span>
            </x-artisanpack-button>
        </div>
    </form>
</div>
```

## Account Deletion

### Delete Account Component

Secure account deletion with confirmation:

```php
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use function Livewire\Volt\{state, rules};

state([
    'password' => '',
    'confirmingDeletion' => false,
]);

rules([
    'password' => ['required', 'string'],
]);

$confirmDeletion = fn() => $this->confirmingDeletion = true;
$cancelDeletion = fn() => $this->confirmingDeletion = false;

$deleteAccount = function () {
    $this->validate();
    
    $user = auth()->user();
    
    if (!Hash::check($this->password, $user->password)) {
        throw ValidationException::withMessages([
            'password' => 'The password is incorrect.',
        ]);
    }
    
    // Log account deletion
    Log::info('Account deleted', ['user_id' => $user->id]);
    
    // Delete user data
    $user->delete();
    
    // Logout and redirect
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    $this->redirect('/', navigate: true);
};

?>

<div>
    @if(!$confirmingDeletion)
        <x-artisanpack-button wire:click="confirmDeletion" variant="danger">
            Delete Account
        </x-artisanpack-button>
    @else
        <x-artisanpack-card>
            <x-artisanpack-card.header>
                <x-artisanpack-heading size="lg" class="text-red-600">Delete Account</x-artisanpack-heading>
            </x-artisanpack-card.header>
            
            <div class="space-y-4">
                <x-artisanpack-text>
                    This action cannot be undone. All your data will be permanently deleted.
                </x-artisanpack-text>
                
                <x-artisanpack-input
                    wire:model="password"
                    label="Confirm with your password"
                    type="password"
                    placeholder="Enter your password to confirm"
                    required
                    :error="$errors->first('password')"
                />
                
                <div class="flex space-x-4">
                    <x-artisanpack-button wire:click="deleteAccount" variant="danger">
                        <span wire:loading.remove wire:target="deleteAccount">Delete Account</span>
                        <span wire:loading wire:target="deleteAccount">Deleting...</span>
                    </x-artisanpack-button>
                    
                    <x-artisanpack-button wire:click="cancelDeletion" variant="ghost">
                        Cancel
                    </x-artisanpack-button>
                </div>
            </div>
        </x-artisanpack-card>
    @endif
</div>
```

## Settings Layout

### Settings Navigation

Create a consistent navigation for settings pages:

```blade
<!-- resources/views/components/settings/layout.blade.php -->
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex">
                    <!-- Settings Navigation -->
                    <div class="w-64 bg-gray-50 p-6">
                        <x-artisanpack-heading size="lg" class="mb-4">Settings</x-artisanpack-heading>
                        
                        <nav class="space-y-2">
                            <a href="{{ route('settings.profile') }}" 
                               @class([
                                   'flex items-center px-3 py-2 rounded-md text-sm font-medium',
                                   'bg-primary-100 text-primary-700' => request()->routeIs('settings.profile'),
                                   'text-gray-600 hover:text-gray-900 hover:bg-gray-100' => !request()->routeIs('settings.profile'),
                               ])>
                                <x-artisanpack-icon name="user" class="w-5 h-5 mr-3" />
                                Profile
                            </a>
                            
                            <a href="{{ route('settings.password') }}" 
                               @class([
                                   'flex items-center px-3 py-2 rounded-md text-sm font-medium',
                                   'bg-primary-100 text-primary-700' => request()->routeIs('settings.password'),
                                   'text-gray-600 hover:text-gray-900 hover:bg-gray-100' => !request()->routeIs('settings.password'),
                               ])>
                                <x-artisanpack-icon name="key" class="w-5 h-5 mr-3" />
                                Password
                            </a>
                            
                            <a href="{{ route('settings.preferences') }}" 
                               @class([
                                   'flex items-center px-3 py-2 rounded-md text-sm font-medium',
                                   'bg-primary-100 text-primary-700' => request()->routeIs('settings.preferences'),
                                   'text-gray-600 hover:text-gray-900 hover:bg-gray-100' => !request()->routeIs('settings.preferences'),
                               ])>
                                <x-artisanpack-icon name="cog" class="w-5 h-5 mr-3" />
                                Preferences
                            </a>
                            
                            <a href="{{ route('settings.account') }}" 
                               @class([
                                   'flex items-center px-3 py-2 rounded-md text-sm font-medium',
                                   'bg-primary-100 text-primary-700' => request()->routeIs('settings.account'),
                                   'text-gray-600 hover:text-gray-900 hover:bg-gray-100' => !request()->routeIs('settings.account'),
                               ])>
                                <x-artisanpack-icon name="trash" class="w-5 h-5 mr-3" />
                                Account
                            </a>
                        </nav>
                    </div>
                    
                    <!-- Settings Content -->
                    <div class="flex-1 p-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

## User Model Extensions

### Preferences Attribute

Add preferences support to the User model:

```php
class User extends Authenticatable implements MustVerifyEmail
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'preferences',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
        ];
    }
    
    /**
     * Get user's theme preference
     */
    public function getThemeAttribute(): string
    {
        return $this->preferences['theme'] ?? 'system';
    }
    
    /**
     * Check if user has notifications enabled
     */
    public function hasNotificationsEnabled(): bool
    {
        return $this->preferences['notifications_enabled'] ?? true;
    }
}
```

### Migration for Preferences

Add preferences column to users table:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('preferences')->nullable()->after('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferences');
        });
    }
};
```

## Testing User Settings

### Profile Update Tests

```php
test('users can update their profile', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->put('/settings/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])
        ->assertSuccessful();
    
    $user->refresh();
    
    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('updated@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email change requires verification', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    
    $this->actingAs($user)
        ->put('/settings/profile', [
            'name' => $user->name,
            'email' => 'new@example.com',
        ])
        ->assertSuccessful();
    
    $user->refresh();
    expect($user->email_verified_at)->toBeNull();
});
```

### Password Update Tests

```php
test('users can update their password', function () {
    $user = User::factory()->create();
    $originalPassword = $user->password;
    
    $this->actingAs($user)
        ->put('/settings/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertSuccessful();
    
    $user->refresh();
    expect($user->password)->not->toBe($originalPassword);
    expect(Hash::check('new-password', $user->password))->toBeTrue();
});

test('current password must be correct', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->put('/settings/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertSessionHasErrors('current_password');
});
```

## Best Practices

1. **Always verify current password** for sensitive changes
2. **Invalidate sessions** when password changes
3. **Require email verification** for email changes
4. **Provide clear feedback** for all actions
5. **Use proper validation** for all inputs
6. **Log important changes** for security auditing

## Security Considerations

- **Password verification** required for sensitive operations
- **Session invalidation** on password changes
- **Rate limiting** on sensitive endpoints
- **Audit logging** for account changes
- **CSRF protection** on all forms

## Next Steps

- Learn about [Security](security) best practices
- Explore [Password Reset](password-reset) functionality
- Review [Email Verification](email-verification) system
- Check [Customization](customization) options