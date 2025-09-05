---
title: Authentication
---

# Authentication

The Livewire Starter Kit includes a complete authentication system with modern features and security best practices.

## Overview

The authentication system provides:

- **User Registration** with email verification
- **Login and Logout** functionality
- **Password Reset** via email
- **Profile Management** with settings
- **Email Verification** for new accounts
- **Session Management** and security
- **Remember Me** functionality

## Quick Start

The authentication system is ready to use out of the box. Users can:

1. Register new accounts at `/register`
2. Login at `/login`
3. Reset passwords at `/forgot-password`
4. Verify emails via sent links
5. Manage profiles at `/settings/profile`

## Authentication Routes

All authentication routes are defined in `routes/auth.php`:

```php
// Registration
Route::get('register', RegisterPage::class)->name('register');

// Login
Route::get('login', LoginPage::class)->name('login');

// Password Reset
Route::get('forgot-password', RequestPasswordResetPage::class)->name('password.request');
Route::get('reset-password/{token}', ResetPasswordPage::class)->name('password.reset');

// Email Verification
Route::get('verify-email', EmailVerificationPromptPage::class)->name('verification.notice');
Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->name('verification.verify');

// Logout
Route::post('logout', [LogoutController::class, 'destroy'])->name('logout');
```

## Key Components

### User Model

The `User` model extends Laravel's default with additional features:

```php
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

### Authentication Pages

Authentication is handled by Livewire Volt components:

- **Registration**: Full-featured registration form
- **Login**: Secure login with remember me option
- **Password Reset**: Email-based password reset flow
- **Email Verification**: Automated email verification system

## Detailed Guides

Explore specific authentication topics:

- **[Registration](authentication/registration)** - User registration process and customization
- **[Login](authentication/login)** - Login functionality and security features  
- **[Password Reset](authentication/password-reset)** - Password reset flow and email templates
- **[Email Verification](authentication/email-verification)** - Email verification system
- **[User Settings](authentication/user-settings)** - Profile management and account settings
- **[Security](authentication/security)** - Security features and best practices
- **[Customization](authentication/customization)** - Customizing the authentication system

## Configuration

### Authentication Configuration

Key authentication settings in `config/auth.php`:

```php
'defaults' => [
    'guard' => 'web',
    'passwords' => 'users',
],

'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

### Email Configuration

Configure email settings for authentication emails:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Session Configuration

Configure secure session handling:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

## Security Features

### Password Security
- Passwords are hashed using Laravel's default bcrypt
- Minimum password requirements enforced
- Password confirmation required for sensitive operations

### Session Security
- CSRF protection on all forms
- Secure session handling
- Session regeneration on login

### Email Verification
- Optional email verification for new accounts
- Secure verification tokens
- Automatic cleanup of expired tokens

### Rate Limiting
- Login attempt rate limiting
- Password reset request limiting
- Registration rate limiting

## Middleware

### Authentication Middleware

Protected routes use the `auth` middleware:

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
    Route::get('/settings/profile', ProfilePage::class)->name('settings.profile');
});
```

### Email Verification Middleware

Routes requiring verified emails use `verified` middleware:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
});
```

## Customization Examples

### Custom Registration Fields

Add additional fields to registration:

```php
// In registration component
public string $phone = '';

protected function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string|max:20',
        'password' => 'required|string|min:8|confirmed',
    ];
}
```

### Custom Login Logic

Override login behavior:

```php
public function login(): void
{
    $this->validate();
    
    // Custom authentication logic
    if (Auth::attempt($this->only(['email', 'password']), $this->remember)) {
        // Log successful login
        Log::info('User logged in', ['user_id' => Auth::id()]);
        
        $this->redirect(intended: route('dashboard'));
    }
    
    $this->addError('email', 'Invalid credentials.');
}
```

### Custom Email Templates

Customize authentication email templates in `resources/views/emails/`:

- `verify-email.blade.php` - Email verification
- `reset-password.blade.php` - Password reset

## Testing Authentication

The starter kit includes comprehensive authentication tests:

```bash
# Run authentication tests
php artisan test --filter=Authentication

# Test specific authentication features
php artisan test tests/Feature/Auth/RegistrationTest.php
php artisan test tests/Feature/Auth/LoginTest.php
```

Example test:

```php
test('users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
});
```

## Troubleshooting

### Common Issues

**Email Verification Not Working**
- Check mail configuration in `.env`
- Verify `MAIL_FROM_ADDRESS` is set
- Check spam/junk folders

**Session Issues**
- Clear sessions: `php artisan session:table && php artisan migrate`
- Check session driver configuration
- Verify storage permissions

**Password Reset Issues**
- Check mail configuration
- Verify password reset table exists
- Check token expiration settings

## Next Steps

- Learn about [User Settings](authentication/user-settings) management
- Explore [Security](authentication/security) best practices
- Review [Customization](authentication/customization) options
- Check [Testing](testing) authentication features