---
title: User Login
---

# User Login

The login system provides secure user authentication with features like remember me, rate limiting, and session management.

## Overview

The login system includes:

- **Secure authentication** with email and password
- **Remember Me** functionality for persistent sessions
- **Rate limiting** to prevent brute force attacks
- **Session management** with automatic regeneration
- **Redirect handling** to intended destination
- **CSRF protection** on all forms

## Login Flow

1. User visits `/login`
2. Enters email and password credentials
3. Optionally checks "Remember Me"
4. Form validates credentials
5. Session is created and regenerated
6. User is redirected to intended page or dashboard

## Implementation Details

### Login Component

The login is handled by a Livewire Volt component:

```php
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use function Livewire\Volt\{layout, rules, state};

layout('components.layouts.auth');

state([
    'email' => '',
    'password' => '',
    'remember' => false,
]);

rules([
    'email' => ['required', 'string', 'email'],
    'password' => ['required', 'string'],
]);

$login = function () {
    $this->validate();

    if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    request()->session()->regenerate();

    $this->redirect(intended: route('dashboard', absolute: false), navigate: true);
};

?>

<div>
    <form wire:submit="login">
        <!-- Login form fields -->
    </form>
</div>
```

### Login Form

The login form includes these fields:

```blade
<form wire:submit="login" class="space-y-6">
    <div>
        <x-artisanpack-input
            wire:model="email"
            label="Email"
            type="email"
            placeholder="Enter your email address"
            required
            autofocus
            autocomplete="username"
            :error="$errors->first('email')"
        />
    </div>

    <div>
        <x-artisanpack-input
            wire:model="password"
            label="Password"
            type="password"
            placeholder="Enter your password"
            required
            autocomplete="current-password"
            :error="$errors->first('password')"
        />
    </div>

    <div class="flex items-center justify-between">
        <x-artisanpack-checkbox wire:model="remember" label="Remember me" />
        
        <a href="{{ route('password.request') }}" 
           class="text-sm text-primary-600 hover:text-primary-500">
            Forgot your password?
        </a>
    </div>

    <x-artisanpack-button type="submit" variant="primary" class="w-full">
        <span wire:loading.remove wire:target="login">Sign In</span>
        <span wire:loading wire:target="login">Signing In...</span>
    </x-artisanpack-button>
</form>
```

## Authentication Process

### Credential Verification

Laravel's `Auth::attempt()` method handles credential verification:

```php
if (Auth::attempt($credentials, $remember)) {
    // Authentication successful
    request()->session()->regenerate();
    return redirect()->intended('/dashboard');
}
```

### Password Hashing

Passwords are verified against stored hashes:

```php
// During registration
$user->password = Hash::make($password);

// During login (automatic with Auth::attempt)
if (Hash::check($password, $user->password)) {
    // Password matches
}
```

## Session Management

### Session Regeneration

Sessions are regenerated on login for security:

```php
// Regenerate session ID to prevent fixation attacks
request()->session()->regenerate();

// Alternatively, regenerate and invalidate old session
request()->session()->invalidate();
request()->session()->regenerateToken();
```

### Session Configuration

Configure sessions in `config/session.php`:

```php
'driver' => env('SESSION_DRIVER', 'database'),
'lifetime' => env('SESSION_LIFETIME', 120),
'expire_on_close' => false,
'encrypt' => false,
'files' => storage_path('framework/sessions'),
'connection' => env('SESSION_CONNECTION'),
'table' => 'sessions',
'store' => env('SESSION_STORE'),
'lottery' => [2, 100],
'cookie' => env('SESSION_COOKIE', 'laravel_session'),
'path' => '/',
'domain' => env('SESSION_DOMAIN'),
'secure' => env('SESSION_SECURE_COOKIE'),
'http_only' => true,
'same_site' => 'lax',
```

## Remember Me Functionality

### How Remember Me Works

When "Remember Me" is checked:

1. A persistent login cookie is created
2. Cookie contains an encrypted user identifier
3. Cookie persists beyond session expiration
4. User stays logged in across browser sessions

### Implementation

```php
// Login with remember me
Auth::attempt($credentials, $remember);

// Check if user was remembered
if (Auth::viaRemember()) {
    // User was authenticated via remember token
}

// Logout and forget remember token
Auth::logout();
request()->session()->invalidate();
request()->session()->regenerateToken();
```

### Security Considerations

Remember tokens are:
- Encrypted and signed
- Automatically rotated
- Invalidated on password change
- Limited to specific browser/device

## Rate Limiting

### Login Rate Limiting

Prevent brute force attacks with rate limiting:

```php
// In RouteServiceProvider
RateLimiter::for('login', function (Request $request) {
    $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    
    return Limit::perMinute(5)->by($throttleKey);
});
```

Apply to login route:

```php
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('throttle:login')
    ->name('login');
```

### Failed Login Tracking

Track failed login attempts:

```php
$login = function () {
    $this->validate();
    
    $throttleKey = Str::lower($this->email).'|'.request()->ip();
    
    if (RateLimiter::tooManyAttempts('login:'.$throttleKey, 5)) {
        $seconds = RateLimiter::availableIn('login:'.$throttleKey);
        
        throw ValidationException::withMessages([
            'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
        ]);
    }

    if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
        RateLimiter::hit('login:'.$throttleKey);
        
        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }
    
    RateLimiter::clear('login:'.$throttleKey);

    request()->session()->regenerate();
    $this->redirect(intended: route('dashboard'), navigate: true);
};
```

## Redirect Handling

### Intended Redirect

Redirect users to their intended destination:

```php
// Store intended URL (automatic with auth middleware)
return redirect()->guest(route('login'));

// Redirect to intended URL after login
return redirect()->intended('/dashboard');
```

### Custom Redirect Logic

Implement custom redirect logic:

```php
$login = function () {
    $this->validate();

    if (Auth::attempt($this->only(['email', 'password']), $this->remember)) {
        request()->session()->regenerate();
        
        $user = Auth::user();
        
        // Custom redirect based on user role
        if ($user->hasRole('admin')) {
            $this->redirect('/admin/dashboard');
        } elseif ($user->hasRole('moderator')) {
            $this->redirect('/moderator/dashboard');
        } else {
            $this->redirect('/dashboard');
        }
        
        return;
    }

    throw ValidationException::withMessages([
        'email' => trans('auth.failed'),
    ]);
};
```

## Customization

### Custom Authentication Logic

Add custom authentication logic:

```php
$login = function () {
    $this->validate();
    
    $user = User::where('email', $this->email)->first();
    
    // Custom checks
    if (!$user) {
        throw ValidationException::withMessages([
            'email' => 'No account found with this email address.',
        ]);
    }
    
    if (!$user->is_active) {
        throw ValidationException::withMessages([
            'email' => 'Your account has been deactivated.',
        ]);
    }
    
    if (!Hash::check($this->password, $user->password)) {
        throw ValidationException::withMessages([
            'password' => 'The password is incorrect.',
        ]);
    }
    
    // Log successful login
    Log::info('User logged in', [
        'user_id' => $user->id,
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
    
    Auth::login($user, $this->remember);
    request()->session()->regenerate();
    
    $this->redirect(intended: route('dashboard'), navigate: true);
};
```

### Two-Factor Authentication

Add 2FA support:

```php
// After basic authentication
if ($user && Hash::check($this->password, $user->password)) {
    if ($user->two_factor_enabled) {
        // Store partial authentication
        session(['2fa_user_id' => $user->id]);
        
        // Redirect to 2FA verification
        $this->redirect('/login/two-factor');
        return;
    }
    
    // Complete login
    Auth::login($user, $this->remember);
    request()->session()->regenerate();
}
```

## Security Features

### CSRF Protection

All login forms include CSRF protection:

```blade
<form wire:submit="login">
    @csrf
    <!-- Form fields -->
</form>
```

### Session Security

Additional security measures:

```php
// Force session regeneration on login
request()->session()->regenerate();

// Set secure session cookies in production
'secure' => env('SESSION_SECURE_COOKIE', false),
'http_only' => true,
'same_site' => 'lax',
```

### Password Timing Attack Prevention

Laravel's `Hash::check()` prevents timing attacks by taking constant time regardless of password correctness.

## Testing Login

### Feature Tests

Test the login process:

```php
test('users can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
    $this->assertAuthenticatedAs($user);
});

test('users cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login form validates required fields', function () {
    $response = $this->post('/login', [
        'email' => '',
        'password' => '',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
});
```

### Livewire Tests

Test the Livewire login component:

```php
use Livewire\Volt\Volt;

test('login form validates input', function () {
    Volt::test('pages.auth.login')
        ->set('email', '')
        ->set('password', '')
        ->call('login')
        ->assertHasErrors(['email', 'password']);
});

test('successful login redirects to dashboard', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    Volt::test('pages.auth.login')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->call('login')
        ->assertRedirect('/dashboard');
});

test('remember me functionality works', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    Volt::test('pages.auth.login')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('remember', true)
        ->call('login');

    $this->assertNotNull(Auth::getRecallerName());
});
```

## Troubleshooting

### Common Issues

**Login Not Working**
- Check database connection
- Verify user credentials exist
- Check password hashing algorithm
- Review session configuration

**Rate Limiting Too Aggressive**
- Adjust rate limiting settings
- Check IP address detection
- Review throttle key generation

**Remember Me Not Working**
- Check cookie configuration
- Verify remember token column exists
- Review cookie domain settings

### Debug Login

Add debugging to login process:

```php
$login = function () {
    Log::info('Login attempt', [
        'email' => $this->email,
        'ip' => request()->ip(),
    ]);
    
    $this->validate();
    
    $user = User::where('email', $this->email)->first();
    
    if (!$user) {
        Log::warning('Login failed: User not found', ['email' => $this->email]);
    } else {
        Log::info('User found for login', ['user_id' => $user->id]);
    }

    if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
        Log::warning('Login failed: Invalid credentials', [
            'email' => $this->email,
            'ip' => request()->ip(),
        ]);
        
        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    Log::info('Login successful', [
        'user_id' => Auth::id(),
        'remember' => $this->remember,
    ]);

    request()->session()->regenerate();
    $this->redirect(intended: route('dashboard'), navigate: true);
};
```

## Best Practices

1. **Always validate input** before processing
2. **Implement rate limiting** to prevent brute force attacks
3. **Regenerate sessions** on login for security
4. **Use HTTPS** in production for secure cookies
5. **Log authentication events** for monitoring
6. **Test thoroughly** including edge cases

## Next Steps

- Learn about [Password Reset](password-reset) functionality
- Explore [Email Verification](email-verification) system
- Review [Security](security) best practices
- Check [User Settings](user-settings) management