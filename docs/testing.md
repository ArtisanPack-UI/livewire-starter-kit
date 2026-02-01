---
title: Testing
---

# Testing

The Livewire Starter Kit includes comprehensive testing setup using Pest PHP for both unit and feature tests.

## Overview

The testing framework includes:

- **Pest PHP** - Modern testing framework for PHP
- **Feature Tests** - Test complete user workflows
- **Unit Tests** - Test individual components and classes
- **Livewire Tests** - Test reactive components
- **Database Tests** - Test with database interactions
- **Authentication Tests** - Test auth system functionality

## Test Structure

Tests are organized in the `tests/` directory:

```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   ├── RegistrationTest.php
│   │   └── PasswordResetTest.php
│   ├── Settings/
│   │   ├── ProfileTest.php
│   │   └── PasswordTest.php
│   └── ExampleTest.php
├── Unit/
│   └── ExampleTest.php
├── Pest.php
└── TestCase.php
```

## Running Tests

### Basic Test Commands

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/Auth/LoginTest.php

# Run tests matching pattern
php artisan test --filter=login

# Run tests in parallel
php artisan test --parallel

# Run tests with detailed output
php artisan test --verbose
```

### Test Categories

```bash
# Run only feature tests
php artisan test tests/Feature

# Run only unit tests
php artisan test tests/Unit

# Run authentication tests
php artisan test tests/Feature/Auth
```

## Writing Tests

### Basic Pest Test Structure

```php
<?php

use App\Models\User;

test('users can view dashboard', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Dashboard');
});

test('guests cannot view dashboard', function () {
    $this->get('/dashboard')
        ->assertRedirect('/login');
});
```

### Using Datasets

Test with multiple data sets:

```php
test('email validation works', function (string $email, bool $valid) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    
    if ($valid) {
        $response->assertRedirect('/dashboard');
    } else {
        $response->assertSessionHasErrors('email');
    }
})->with([
    'valid email' => ['test@example.com', true],
    'invalid email' => ['invalid-email', false],
    'empty email' => ['', false],
]);
```

### Test Hooks

```php
beforeEach(function () {
    // Runs before each test
    $this->user = User::factory()->create();
});

afterEach(function () {
    // Runs after each test
    // Cleanup operations
});
```

## Feature Testing

### Authentication Tests

Test complete authentication workflows:

```php
<?php

use App\Models\User;

test('users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    
    $this->assertAuthenticated();
});

test('users can login', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/');
        
    $this->assertGuest();
});
```

### API Testing

Test API endpoints:

```php
test('api returns user data', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonStructure([
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ])
        ->assertJson([
            'id' => $user->id,
            'email' => $user->email,
        ]);
});

test('api validates required fields', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->postJson('/api/posts', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'content']);
});
```

## Livewire Testing

### Testing Volt Components

```php
use Livewire\Volt\Volt;

test('counter increments correctly', function () {
    Volt::test('counter')
        ->assertSee('0')
        ->call('increment')
        ->assertSee('1')
        ->call('increment')
        ->assertSee('2');
});

test('form validation works', function () {
    Volt::test('contact-form')
        ->set('email', 'invalid-email')
        ->set('message', '')
        ->call('submit')
        ->assertHasErrors(['email', 'message']);
});

test('form submits successfully', function () {
    Volt::test('contact-form')
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('message', 'Hello world')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatched('form-submitted');
});
```

### Testing Traditional Livewire Components

```php
use Livewire\Livewire;
use App\Livewire\PostsList;

test('posts list displays posts', function () {
    $posts = Post::factory()->count(5)->create();
    
    Livewire::test(PostsList::class)
        ->assertSee($posts->first()->title)
        ->assertSee($posts->last()->title);
});

test('posts can be filtered', function () {
    $post1 = Post::factory()->create(['title' => 'Laravel Testing']);
    $post2 = Post::factory()->create(['title' => 'Vue.js Guide']);
    
    Livewire::test(PostsList::class)
        ->set('search', 'Laravel')
        ->assertSee('Laravel Testing')
        ->assertDontSee('Vue.js Guide');
});
```

### Testing Component Events

```php
test('component dispatches events', function () {
    Volt::test('create-post')
        ->set('title', 'New Post')
        ->set('content', 'Post content')
        ->call('save')
        ->assertDispatched('post-created')
        ->assertDispatched('post-created', ['id' => 1]);
});

test('component listens to events', function () {
    Volt::test('notification-banner')
        ->dispatch('show-notification', message: 'Success!')
        ->assertSee('Success!');
});
```

## Database Testing

### Using Factories

Create test data with factories:

```php
test('user can create posts', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->post('/posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
        ])
        ->assertRedirect('/posts');
        
    $this->assertDatabaseHas('posts', [
        'title' => 'Test Post',
        'user_id' => $user->id,
    ]);
});
```

### Database Assertions

```php
test('user deletion removes related data', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    $user->delete();
    
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

test('database seeding works', function () {
    $this->seed(UserSeeder::class);
    
    $this->assertDatabaseCount('users', 10);
});
```

### Transactions and Cleanup

```php
test('database is cleaned up between tests', function () {
    User::factory()->create(['email' => 'test@example.com']);
    
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('each test starts with clean database', function () {
    // This test should not see the user from the previous test
    $this->assertDatabaseEmpty('users');
});
```

## Mocking and Stubbing

### Mocking External Services

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

test('welcome email is sent on registration', function () {
    Mail::fake();
    
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    
    Mail::assertSent(WelcomeEmail::class, function ($mail) {
        return $mail->hasTo('test@example.com');
    });
});

test('notification is queued', function () {
    Notification::fake();
    
    $user = User::factory()->create();
    
    // Trigger notification
    $user->notify(new WelcomeNotification());
    
    Notification::assertSentTo($user, WelcomeNotification::class);
});
```

### Mocking HTTP Requests

```php
use Illuminate\Support\Facades\Http;

test('external api call works', function () {
    Http::fake([
        'api.example.com/*' => Http::response(['status' => 'success'], 200),
    ]);
    
    $response = $this->post('/sync-data');
    
    $response->assertOk();
    
    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.example.com/sync';
    });
});
```

## Performance Testing

### Testing Response Times

```php
test('dashboard loads quickly', function () {
    $user = User::factory()->create();
    
    $start = microtime(true);
    
    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk();
        
    $end = microtime(true);
    $duration = ($end - $start) * 1000; // Convert to milliseconds
    
    expect($duration)->toBeLessThan(500); // Should load in under 500ms
});
```

### Testing Memory Usage

```php
test('bulk operations do not exceed memory limit', function () {
    $startMemory = memory_get_usage();
    
    // Perform bulk operation
    User::factory()->count(1000)->create();
    
    $endMemory = memory_get_usage();
    $memoryUsed = $endMemory - $startMemory;
    
    expect($memoryUsed)->toBeLessThan(50 * 1024 * 1024); // Less than 50MB
});
```

## Testing Configuration

### Test Environment

Configure testing environment in `phpunit.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_STORE" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="PULSE_ENABLED" value="false"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

### Database Configuration

Set up testing database:

```php
// config/database.php
'testing' => [
    'driver' => 'sqlite',
    'database' => ':memory:',
    'prefix' => '',
],
```

## Continuous Integration

### GitHub Actions Example

```yaml
# .github/workflows/tests.yml
name: Tests

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        extensions: mbstring, dom, fileinfo, mysql
        
    - name: Install dependencies
      run: composer install --prefer-dist --no-progress
      
    - name: Generate application key
      run: php artisan key:generate --env=testing
      
    - name: Run tests
      run: php artisan test --coverage
```

## Test Organization Best Practices

### Grouping Tests

```php
// Group related tests
describe('User Authentication', function () {
    test('users can register', function () {
        // Test registration
    });
    
    test('users can login', function () {
        // Test login
    });
    
    test('users can logout', function () {
        // Test logout
    });
});

describe('User Profile', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });
    
    test('users can update profile', function () {
        // Test profile update
    });
    
    test('users can change password', function () {
        // Test password change
    });
});
```

### Test Naming Conventions

```php
// Good test names
test('user can register with valid data')
test('registration fails with invalid email')
test('authenticated user can view dashboard')
test('guest user is redirected to login')

// Avoid generic names
test('test user registration')
test('test dashboard')
```

## Debugging Tests

### Debug Output

```php
test('debug test data', function () {
    $user = User::factory()->create();
    
    // Debug output
    dump($user->toArray());
    ray($user); // Using Ray debugger
    
    $this->actingAs($user)
        ->get('/dashboard')
        ->dump() // Dump response
        ->assertOk();
});
```

### Pausing Tests

```php
test('pause for inspection', function () {
    $user = User::factory()->create();
    
    // Pause test execution
    $this->withoutExceptionHandling();
    
    $this->actingAs($user)
        ->get('/dashboard')
        ->dd(); // Dump and die
});
```

## Coverage Reports

Generate test coverage reports:

```bash
# Generate HTML coverage report
php artisan test --coverage-html reports

# Generate text coverage report
php artisan test --coverage-text

# Set minimum coverage threshold
php artisan test --min=80
```

## Best Practices

1. **Write descriptive test names** that explain what is being tested
2. **Keep tests focused** on single functionality
3. **Use factories** for consistent test data
4. **Mock external dependencies** to isolate tests
5. **Test both happy path and edge cases**
6. **Maintain good test coverage** (aim for 80%+)
7. **Keep tests fast** by using in-memory database
8. **Group related tests** using describe blocks
9. **Use appropriate assertions** for better error messages
10. **Clean up after tests** to prevent side effects

## Troubleshooting

### Common Issues

**Tests Running Slowly**
- Use in-memory SQLite database
- Mock external services
- Use database transactions

**Memory Issues**
- Reduce factory usage
- Clear collections after use
- Use refresh database trait

**Flaky Tests**
- Fix timing issues with proper waits
- Ensure proper test isolation
- Use consistent test data

## Next Steps

- Explore [Authentication](Authentication) testing in detail
- Learn about [Components](Components) testing strategies
- Review [Deployment](Deployment) testing practices
- Check [Troubleshooting](Troubleshooting) for test issues