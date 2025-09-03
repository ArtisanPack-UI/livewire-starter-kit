# CMS Framework User Model Reference Update Implementation Plan

## Overview

When users select the `cms-framework` package to install via `OptionalPackagesCommand.php`, the default Laravel `App\Models\User` model is deleted and replaced with the CMS framework's `ArtisanPackUI\CMSFramework\Models\User` model. However, existing code throughout the application still references the deleted model, causing fatal errors.

**CRITICAL ISSUE IDENTIFIED:** Users are experiencing `Class "\App\Models\User" not found` errors during application bootstrap (specifically at `public/index.php:20`) when trying to load pages after CMS framework installation. This error occurs because the authentication configuration and Livewire components still reference the deleted User model, causing failures during early request processing.

This document outlines a comprehensive plan to automatically update all references to `App\Models\User` to point to the new CMS framework User model during installation, with **immediate priority on bootstrap-critical files**.

## Current State Analysis

### Files Currently Handled
The `OptionalPackagesCommand.php` already handles:
- ✅ Deletes `app/Models/User.php`
- ✅ Deletes the users migration file
- ✅ **CONFIRMED WORKING:** Replaces `resources/views/livewire/auth/register.blade.php` with CMS framework version (uses `ArtisanPackUI\CMSFramework\Models\User`)
- ✅ **CONFIRMED WORKING:** Replaces `resources/views/livewire/settings/profile.blade.php` with CMS framework version (uses `ArtisanPackUI\CMSFramework\Models\User`)

**Updated Analysis:** The stub replacement process in `OptionalPackagesCommand.php` is working correctly. The Livewire components are properly using the CMS framework User model after installation. The bootstrap errors are caused by other files that still reference the deleted App\Models\User.

### Files That Still Need Updates
Based on project analysis, 10 files contain `App\Models\User` references that need updating:

#### PRIORITY 1: Bootstrap-Critical Files (1 file)
**This file causes immediate application failure and must be updated first:**

- `config/auth.php` - Line 65: `'model' => env('AUTH_MODEL', App\Models\User::class),`
  - **Impact:** Causes fatal error during authentication system bootstrap

#### PRIORITY 2: Database Files (2 files)
**These files affect database operations but don't cause immediate bootstrap failures:**

- `database/factories/UserFactory.php` - Line 10: PHPDoc comment `@extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>`
- `database/seeders/DatabaseSeeder.php` - Line 5: `use App\Models\User;`

#### PRIORITY 3: Test Files (8 files)
**These files only affect testing and don't impact production functionality:**

All test files follow the same pattern with `use App\Models\User;` import statements:
- `tests/Feature/Auth/AuthenticationTest.php`
- `tests/Feature/Auth/EmailVerificationTest.php`
- `tests/Feature/Auth/PasswordConfirmationTest.php`
- `tests/Feature/Auth/PasswordResetTest.php`
- `tests/Feature/DashboardTest.php`
- `tests/Feature/Settings/PasswordUpdateTest.php`
- `tests/Feature/Settings/ProfileUpdateTest.php`

## Implementation Strategy

### **IMMEDIATE PRIORITY: Fix Bootstrap-Critical File**

The primary issue causing the `Class "\App\Models\User" not found` bootstrap error is the `config/auth.php` file, which still references the deleted User model in the authentication configuration.

### File Update Categories (Priority-Based)

#### Category 1: Bootstrap-Critical Files (URGENT)
**This file causes immediate application failure and must be fixed first:**

**Files:**
- `config/auth.php` - Simple string replacement

**Action Required:**
1. **Add config/auth.php update:** Currently not handled by OptionalPackagesCommand.php but is the root cause of bootstrap failures

#### Category 2: Database Files (MEDIUM PRIORITY)
**These files affect database operations but don't cause bootstrap failures:**

**Files:**
- `database/seeders/DatabaseSeeder.php` - Simple string replacement
- `database/factories/UserFactory.php` - Complex update (structural differences)

**Issues with UserFactory:**
- PHPDoc comment needs updating
- Factory definition uses Laravel's default User fields (`name`) but CMS framework User model uses different fields (`first_name`, `last_name`, `username`)
- Requires complete stub replacement rather than simple string replacement

#### Category 3: Test Files (LOW PRIORITY)
**These files only affect testing and don't impact production functionality:**

**Files:** All 8 test files with `use App\Models\User;` import statements

**Replacement Pattern for Categories 2 & 3:**
- Find: `App\Models\User`
- Replace: `ArtisanPackUI\CMSFramework\Models\User`

### Recommended Implementation Approach

#### Phase 1: Enhance OptionalPackagesCommand.php
Add a new method `updateUserModelReferences()` to handle the automatic updates:

```php
private function updateUserModelReferences(): void
{
    $this->info('Updating User model references...');
    
    // Category 1: Bootstrap-critical file (simple replacement)
    $this->updateFileReferences(config_path('auth.php'));
    
    // Category 2: Database files (simple replacement)
    $this->updateFileReferences(database_path('seeders/DatabaseSeeder.php'));
    
    // Category 3: Test files (simple replacements)
    $testFiles = [
        base_path('tests/Feature/Auth/AuthenticationTest.php'),
        base_path('tests/Feature/Auth/EmailVerificationTest.php'),
        base_path('tests/Feature/Auth/PasswordConfirmationTest.php'),
        base_path('tests/Feature/Auth/PasswordResetTest.php'),
        base_path('tests/Feature/DashboardTest.php'),
        base_path('tests/Feature/Settings/PasswordUpdateTest.php'),
        base_path('tests/Feature/Settings/ProfileUpdateTest.php'),
    ];
    
    foreach ($testFiles as $file) {
        $this->updateFileReferences($file);
    }
    
    // Category 2: Complex updates - UserFactory
    $this->updateUserFactory();
    
    $this->info('User model references updated successfully.');
}

private function updateFileReferences(string $filePath): void
{
    if (!File::exists($filePath)) {
        return;
    }
    
    $content = File::get($filePath);
    $updatedContent = str_replace(
        'App\Models\User',
        'ArtisanPackUI\CMSFramework\Models\User',
        $content
    );
    
    if ($content !== $updatedContent) {
        File::put($filePath, $updatedContent);
        $this->info("Updated references in: " . basename($filePath));
    }
}

private function updateUserFactory(): void
{
    $factoryPath = database_path('factories/UserFactory.php');
    
    if (!File::exists($factoryPath)) {
        return;
    }
    
    // Option 1: Create a stub file for UserFactory
    // Option 2: Programmatically update the factory
    
    // Recommended: Create a stub file that matches CMS framework User model structure
    $stubPath = base_path('stubs/cms-framework/UserFactory.php');
    if (File::exists($stubPath)) {
        File::copy($stubPath, $factoryPath);
        $this->info('Updated UserFactory with CMS framework version.');
    } else {
        // Fallback: Simple string replacement
        $this->updateFileReferences($factoryPath);
    }
}
```

#### Phase 2: Create Additional Stub Files
Create stub files for complex replacements:

**New file:** `stubs/cms-framework/UserFactory.php`
```php
<?php

namespace Database\Factories;

use ArtisanPackUI\CMSFramework\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\ArtisanPackUI\CMSFramework\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

**New file:** `stubs/cms-framework/DatabaseSeeder.php`
```php
<?php

namespace Database\Seeders;

use ArtisanPackUI\CMSFramework\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }
}
```

#### Phase 3: Update OptionalPackagesCommand.php Integration
Modify the existing cms-framework installation block:

```php
if (in_array('artisanpack-ui/cms-framework', $packages)) {
    $this->info('Installing cms-framework and making necessary modifications...');

    // Remove User model and migration (existing code - working correctly)
    File::delete(app_path('Models/User.php'));
    $userMigration = collect(glob(database_path('migrations/*_create_users_table.php')))->first();
    if ($userMigration) {
        File::delete($userMigration);
    }

    // Copy stubs (existing code - working correctly)
    File::copy(base_path('stubs/cms-framework/register.blade.php'), resource_path('views/livewire/auth/register.blade.php'));
    File::copy(base_path('stubs/cms-framework/profile.blade.php'), resource_path('views/livewire/settings/profile.blade.php'));
    
    // NEW: Copy additional stub files for complex updates
    File::copy(base_path('stubs/cms-framework/UserFactory.php'), database_path('factories/UserFactory.php'));
    File::copy(base_path('stubs/cms-framework/DatabaseSeeder.php'), database_path('seeders/DatabaseSeeder.php'));
    
    // NEW: Update remaining file references (config/auth.php and test files)
    $this->updateUserModelReferences();

    $this->info('CMS Framework modifications complete.');
}
```

## Implementation Considerations

### Error Handling
- Check file existence before attempting updates
- Validate file permissions
- Provide informative error messages
- Consider rollback mechanisms for failed installations

### Edge Cases
- Files that might be customized by users
- Third-party packages that might reference the User model
- Custom middleware or service providers that use the User model

### Testing Strategy
- Create test cases that verify all references are updated correctly
- Test with fresh Laravel installation
- Test with existing customizations
- Verify that all functionality works after updates

### Validation
- After updates, verify that:
  - All files compile without errors
  - Tests pass
  - Authentication works
  - Factory and seeders function correctly

## Alternative Approaches

### Approach 1: Global Search and Replace (Not Recommended)
Pros: Simple to implement
Cons: Risk of false positives, harder to handle edge cases

### Approach 2: AST-based Parsing (Overkill)
Pros: More precise
Cons: Complex implementation for this use case

### Approach 3: Stub File Replacement (Recommended)
Pros: Ensures consistency, handles structural changes
Cons: More files to maintain

## Conclusion

The recommended implementation uses a hybrid approach:
1. **Simple string replacement** for straightforward import statements and references
2. **Stub file replacement** for files with structural differences (UserFactory, DatabaseSeeder)
3. **Enhanced error handling** and validation

This approach balances implementation complexity with reliability, ensuring that users can install the cms-framework package without manual intervention while maintaining code quality and functionality.

## Next Steps

1. Create the additional stub files (`UserFactory.php`, `DatabaseSeeder.php`)
2. Implement the `updateUserModelReferences()` method
3. Update the cms-framework installation block in `OptionalPackagesCommand.php`
4. Test the implementation thoroughly
5. Document the changes for end users