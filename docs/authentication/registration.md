---
title: User Registration
---

# User Registration

The registration system allows new users to create accounts with email verification and secure password handling.

## Overview

The registration process includes:

- **User-friendly form** with validation
- **Email verification** (optional)
- **Secure password hashing**
- **CSRF protection**
- **Rate limiting** to prevent abuse
- **Customizable fields** and validation rules

## Registration Flow

1. User visits `/register`
2. Fills out registration form
3. Form validates client-side and server-side
4. Account is created with hashed password
5. Email verification sent (if enabled)
6. User is redirected to dashboard or verification notice

## Registration Form

The registration form includes these default fields:

- **Name**: Full name of the user
- **Email**: Email address (must be unique)
- **Password**: Secure password with confirmation
- **Terms**: Agreement to terms and conditions (optional)

## Implementation Details

### Registration Component

The registration is handled by a Livewire Volt component:

```php
<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use function Livewire\Volt\{layout, rules, state};

layout('components.layouts.auth');

state([
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => '',
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(route('dashboard', absolute: false), navigate: true);
};

?>

<div>
    <form wire:submit="register">
        <!-- Registration form fields -->
    </form>
</div>
```

### Form Validation

#### Client-Side Validation

Real-time validation using Livewire:

```blade
<x-artisanpack-input 
    wire:model.live="email" 
    label="Email" 
    type="email"
    placeholder="Enter your email address"
    required
    :error="$errors->first('email')"
/>
```

#### Server-Side Validation

Comprehensive validation rules:

```php
rules([
    'name' => [
        'required',
        'string',
        'max:255',
        'regex:/^[a-zA-Z\s]+$/', // Only letters and spaces
    ],
    'email' => [
        'required',
        'string',
        'lowercase',
        'email',
        'max:255',
        'unique:users',
    ],
    'password' => [
        'required',
        'string',
        'confirmed',
        Rules\Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols(),
    ],
]);
```

### Password Requirements

Default password requirements:
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one symbol

Customize in `config/auth.php`:

```php
'password_timeout' => 10800,

'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

## Email Verification

### Enabling Email Verification

The `User` model implements `MustVerifyEmail`:

```php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    // Model implementation
}
```

### Verification Process

1. User registers
2. `Registered` event is fired
3. Verification email is sent automatically
4. User clicks verification link
5. Email is marked as verified

### Verification Email Template

Customize the verification email in `resources/views/emails/verify-email.blade.php`:

```blade
<x-mail::message>
# Verify Email Address

Please click the button below to verify your email address.

<x-mail::button :url="$actionUrl">
Verify Email Address
</x-mail::button>

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
```

## Customization

### Adding Custom Fields

Add additional fields to registration:

```php
state([
    'name' => '',
    'email' => '',
    'phone' => '',
    'company' => '',
    'password' => '',
    'password_confirmation' => '',
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users'],
    'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]+$/'],
    'company' => ['nullable', 'string', 'max:255'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
]);
```

Update the User model:

```php
protected $fillable = [
    'name',
    'email',
    'phone',
    'company',
    'password',
];
```

### Custom Validation Messages

Add custom error messages:

```php
protected $messages = [
    'email.unique' => 'This email address is already registered.',
    'password.confirmed' => 'The password confirmation does not match.',
    'phone.regex' => 'Please enter a valid phone number.',
];
```

### Registration Events

Hook into registration events:

```php
// In a service provider
Event::listen(Registered::class, function (Registered $event) {
    // Send welcome email
    Mail::to($event->user)->send(new WelcomeEmail($event->user));
    
    // Log registration
    Log::info('New user registered', ['user_id' => $event->user->id]);
    
    // Add to default role
    $event->user->assignRole('user');
});
```

## Rate Limiting

### Registration Rate Limiting

Prevent registration abuse with rate limiting:

```php
// In RouteServiceProvider or middleware
RateLimiter::for('registration', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

Apply to registration route:

```php
Route::get('register', RegisterPage::class)
    ->middleware('throttle:registration')
    ->name('register');
```

## Security Features

### CSRF Protection

All forms include CSRF tokens automatically:

```blade
<form wire:submit="register">
    @csrf
    <!-- Form fields -->
</form>
```

### SQL Injection Prevention

Eloquent ORM prevents SQL injection:

```php
// Safe - uses parameter binding
User::create($validated);

// Safe - uses Eloquent
User::where('email', $email)->first();
```

### XSS Prevention

Blade templates escape output by default:

```blade
<!-- Safe - automatically escaped -->
<p>Welcome {{ $user->name }}</p>

<!-- Unsafe - only use with trusted content -->
<p>Welcome {!! $user->name !!}</p>
```

## Testing Registration

### Feature Tests

Test the registration process:

```php
test('users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'SecurePassword123!',
        'password_confirmation' => 'SecurePassword123!',
    ]);

    $response->assertRedirect('/dashboard');
    
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    
    $this->assertAuthenticated();
});

test('registration requires valid email', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'SecurePassword123!',
        'password_confirmation' => 'SecurePassword123!',
    ]);

    $response->assertSessionHasErrors('email');
});

test('registration prevents duplicate emails', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'SecurePassword123!',
        'password_confirmation' => 'SecurePassword123!',
    ]);

    $response->assertSessionHasErrors('email');
});
```

### Livewire Tests

Test the Livewire component directly:

```php
use Livewire\Volt\Volt;

test('registration form validates input', function () {
    Volt::test('pages.auth.register')
        ->set('name', '')
        ->set('email', 'invalid')
        ->set('password', '123')
        ->set('password_confirmation', '456')
        ->call('register')
        ->assertHasErrors(['name', 'email', 'password']);
});

test('successful registration redirects to dashboard', function () {
    Volt::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'SecurePassword123!')
        ->set('password_confirmation', 'SecurePassword123!')
        ->call('register')
        ->assertRedirect('/dashboard');
        
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});
```

## Troubleshooting

### Common Issues

**Email Verification Not Sending**
- Check mail configuration in `.env`
- Verify `MAIL_FROM_ADDRESS` is set
- Check queue configuration if using queued emails

**Validation Errors Not Showing**
- Ensure error display is implemented in Blade template
- Check Livewire validation rules syntax
- Verify form submission is using `wire:submit`

**Registration Not Working**
- Check database connection
- Verify User model fillable fields
- Check for middleware conflicts

### Debug Registration

Add debugging to registration:

```php
$register = function () {
    Log::info('Registration attempt', $this->all());
    
    try {
        $validated = $this->validate();
        Log::info('Validation passed', $validated);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        
        Log::info('User created', ['user_id' => $user->id]);
        
        event(new Registered($user));
        Auth::login($user);
        
        $this->redirect(route('dashboard'), navigate: true);
    } catch (\Exception $e) {
        Log::error('Registration failed', ['error' => $e->getMessage()]);
        throw $e;
    }
};
```

## Best Practices

1. **Always validate input** both client-side and server-side
2. **Use strong password requirements** to improve security
3. **Implement rate limiting** to prevent abuse
4. **Send welcome emails** to improve user experience
5. **Log registration events** for monitoring and analytics
6. **Test thoroughly** including edge cases and security scenarios

## Next Steps

- Learn about [Login](login) functionality
- Explore [Email Verification](email-verification) in detail
- Review [Security](security) best practices
- Check [User Settings](user-settings) management