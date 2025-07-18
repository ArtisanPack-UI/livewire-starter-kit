# CMS Framework Documentation

This document provides comprehensive documentation for the ArtisanPack UI CMS Framework integration in the Livewire Starter Kit.

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [User Management](#user-management)
5. [Content Management](#content-management)
6. [Components](#components)
7. [Customization](#customization)
8. [Troubleshooting](#troubleshooting)

## Introduction

The ArtisanPack UI CMS Framework extends the Livewire Starter Kit with content management capabilities, enhanced user management, role-based access control, and specialized UI components for content management.

### Key Features

- **Enhanced User Model**: Extended user model with additional fields (username, first_name, last_name, etc.)
- **Role-Based Access Control**: Comprehensive permission system for controlling access to features
- **Content Management**: Flexible content types with customizable fields and workflows
- **Specialized Components**: UI components designed specifically for CMS functionality
- **Dark Mode Support**: Full dark mode compatibility throughout the CMS interface

## Installation

### Requirements

- PHP 8.1+
- Laravel 10.0+
- Livewire 3.0+
- ArtisanPack UI Components

### Installation Steps

1. Install the CMS Framework package:

```bash
composer require artisanpack-ui/cms-framework
```

2. Publish the configuration files:

```bash
php artisan vendor:publish --tag=cms-framework-config
```

3. Run the migrations:

```bash
php artisan migrate
```

4. Seed the initial roles and permissions:

```bash
php artisan cms-framework:seed
```

## Configuration

The CMS Framework can be configured through the `config/cms-framework.php` file. Key configuration sections include:

### User Configuration

```php
'user' => [
    'model' => \ArtisanpackUi\CmsFramework\Models\User::class,
    'fields' => [
        'username' => true,
        'first_name' => true,
        'last_name' => true,
        'phone' => false,
        'address' => false,
        'bio' => false,
        'profile_image' => false,
    ],
],
```

### Registration Configuration

```php
'registration' => [
    'collect_additional_info' => false,
    'require_terms_acceptance' => true,
    'show_password_requirements' => true,
    'fields' => [
        'phone' => false,
        'phone_required' => false,
        'address' => false,
        'address_required' => false,
        'bio' => false,
        'bio_required' => false,
        'profile_image' => false,
        'profile_image_required' => false,
        'profile_image_max_size' => 2048, // in KB
    ],
],
```

### Content Types Configuration

```php
'content_types' => [
    'page' => [
        'name' => 'Page',
        'name_plural' => 'Pages',
        'fields' => [
            'title' => [
                'type' => 'text',
                'required' => true,
            ],
            'slug' => [
                'type' => 'slug',
                'from' => 'title',
                'required' => true,
                'unique' => true,
            ],
            'content' => [
                'type' => 'wysiwyg',
                'required' => true,
            ],
            'featured_image' => [
                'type' => 'image',
                'required' => false,
            ],
        ],
        'statuses' => ['draft', 'published', 'archived'],
        'default_status' => 'draft',
    ],
    // Add more content types as needed
],
```

## User Management

### User Model

The CMS Framework provides an extended User model with additional fields and methods:

```php
use ArtisanpackUi\CmsFramework\Models\User;

$user = User::find(1);
$user->first_name = 'John';
$user->last_name = 'Doe';
$user->save();

// Check if user has a specific role
if ($user->hasRole('admin')) {
    // Do admin stuff
}

// Check if user has a specific permission
if ($user->hasPermission('edit_pages')) {
    // Allow editing pages
}
```

### Roles and Permissions

The CMS Framework includes a comprehensive role and permission system:

```php
// Assign a role to a user
$user->assignRole('editor');

// Remove a role from a user
$user->removeRole('editor');

// Assign a permission to a user
$user->assignPermission('publish_pages');

// Check if user has any of the specified roles
if ($user->hasAnyRole(['admin', 'editor'])) {
    // User is either an admin or editor
}

// Check if user has all of the specified permissions
if ($user->hasAllPermissions(['edit_pages', 'publish_pages'])) {
    // User can both edit and publish pages
}
```

## Content Management

### Creating Content Types

Content types are defined in the `config/cms-framework.php` file. Each content type can have custom fields, statuses, and permissions.

```php
'content_types' => [
    'article' => [
        'name' => 'Article',
        'name_plural' => 'Articles',
        'fields' => [
            'title' => [
                'type' => 'text',
                'required' => true,
            ],
            'content' => [
                'type' => 'wysiwyg',
                'required' => true,
            ],
            'category' => [
                'type' => 'select',
                'options' => ['News', 'Tutorial', 'Opinion'],
                'required' => true,
            ],
        ],
        'statuses' => ['draft', 'review', 'published', 'archived'],
        'default_status' => 'draft',
    ],
],
```

### Managing Content

Content can be managed through the provided API:

```php
use ArtisanpackUi\CmsFramework\Models\Content;

// Create new content
$article = Content::create([
    'type' => 'article',
    'title' => 'My First Article',
    'content' => '<p>This is my first article content.</p>',
    'category' => 'Tutorial',
    'status' => 'draft',
]);

// Update content
$article->update([
    'title' => 'Updated Article Title',
    'status' => 'published',
]);

// Delete content
$article->delete();

// Find content by type and ID
$article = Content::findByTypeAndId('article', 1);

// Get all published articles
$articles = Content::whereType('article')
    ->whereStatus('published')
    ->orderBy('created_at', 'desc')
    ->get();
```

## Components

The CMS Framework provides specialized components for CMS functionality:

### Role-Based Access Component

The `role-based-access` component allows you to conditionally render content based on user roles and permissions:

```blade
<x-role-based-access role="admin">
    <div>This content is only visible to admins</div>
</x-role-based-access>

<x-role-based-access permission="edit_pages">
    <div>This content is only visible to users with the edit_pages permission</div>
</x-role-based-access>

<x-role-based-access anyRole="admin|editor">
    <div>This content is visible to either admins or editors</div>
</x-role-based-access>

<x-role-based-access allPermissions="edit_pages|publish_pages">
    <div>This content is only visible to users who can both edit and publish pages</div>
</x-role-based-access>

<x-role-based-access guest>
    <div>This content is only visible to guests (non-authenticated users)</div>
</x-role-based-access>

<x-role-based-access auth>
    <div>This content is only visible to authenticated users</div>
</x-role-based-access>
```

### Content Manager Component

The `content-manager` component provides a UI for managing content items:

```blade
<!-- Display a content item with management controls -->
<x-content-manager 
    contentType="article" 
    contentId="1" 
    showTitle 
    showStatus 
    showActions 
    allowEdit 
    allowDelete 
    allowPublish
>
    <!-- Optional custom content -->
    <div>Additional information or custom UI elements</div>
</x-content-manager>

<!-- Display a compact version -->
<x-content-manager 
    contentType="article" 
    contentId="1" 
    compact 
    inline
/>

<!-- Create button for a content type -->
<x-content-manager contentType="article" />
```

## Customization

### Custom User Fields

You can add custom fields to the User model by extending it:

```php
namespace App\Models;

use ArtisanpackUi\CmsFramework\Models\User as CmsUser;

class User extends CmsUser
{
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'custom_field', // Add your custom field
    ];
    
    // Add custom methods or relationships
    public function customRelation()
    {
        return $this->hasMany(CustomModel::class);
    }
}
```

Then update the configuration to use your custom model:

```php
// config/cms-framework.php
'user' => [
    'model' => \App\Models\User::class,
    // ...
],
```

### Custom Content Types

You can create custom content types with specialized behavior by extending the base Content model:

```php
namespace App\Models;

use ArtisanpackUi\CmsFramework\Models\Content as CmsContent;

class Article extends CmsContent
{
    protected $contentType = 'article';
    
    // Add custom methods or relationships
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // Add custom behavior
    public function publish()
    {
        $this->status = 'published';
        $this->published_at = now();
        $this->save();
        
        // Additional publishing logic
        event(new ArticlePublished($this));
    }
}
```

### Custom Components

You can create custom components that extend the CMS Framework components:

```blade
<!-- resources/views/components/admin-panel.blade.php -->
@props(['user'])

<div class="admin-panel">
    <h2>Admin Panel for {{ $user->first_name }} {{ $user->last_name }}</h2>
    
    <x-role-based-access role="admin">
        <div class="admin-actions">
            <h3>Administrative Actions</h3>
            <!-- Admin-specific actions -->
        </div>
    </x-role-based-access>
    
    <x-role-based-access role="editor">
        <div class="editor-actions">
            <h3>Editor Actions</h3>
            <!-- Editor-specific actions -->
        </div>
    </x-role-based-access>
    
    <x-content-manager contentType="article" />
</div>
```

## Troubleshooting

### Common Issues and Solutions

#### Issue: User roles or permissions not working

**Solution:**
1. Ensure the roles and permissions are properly seeded:
   ```bash
   php artisan cms-framework:seed
   ```
2. Check if the user has the correct roles assigned:
   ```php
   $user = User::find(1);
   dd($user->roles); // Debug roles
   ```
3. Verify that the `role-based-access` component is used correctly with the appropriate attributes.

#### Issue: Content types not appearing

**Solution:**
1. Ensure the content types are properly configured in `config/cms-framework.php`.
2. Check if the content type configuration has been published:
   ```bash
   php artisan vendor:publish --tag=cms-framework-config
   ```
3. Clear the configuration cache:
   ```bash
   php artisan config:clear
   ```

#### Issue: Custom fields not saving

**Solution:**
1. Ensure the fields are included in the `$fillable` array of the model.
2. Check if the database migration has been run to add the custom fields.
3. Verify that the form is correctly binding to the model fields.

#### Issue: Migration errors during installation

**Solution:**
1. Ensure your database connection is configured correctly.
2. Try running migrations with the `--step` option to identify problematic migrations:
   ```bash
   php artisan migrate --step
   ```
3. If a specific migration is failing, check for any conflicts with existing tables or columns.

#### Issue: Component rendering errors

**Solution:**
1. Check for syntax errors in your Blade templates.
2. Ensure all required props are provided to the components.
3. Verify that the components are registered correctly.
4. Clear the view cache:
   ```bash
   php artisan view:clear
   ```

### Getting Help

If you encounter issues not covered in this documentation, you can:

1. Check the [GitHub repository](https://github.com/artisanpack-ui/cms-framework) for known issues.
2. Join the [ArtisanPack UI Discord community](https://discord.gg/artisanpack-ui) for real-time help.
3. Submit an issue on the [GitHub issue tracker](https://github.com/artisanpack-ui/cms-framework/issues).
4. Contact support at support@artisanpack-ui.com.

## Version Compatibility

| CMS Framework Version | Laravel Version | Livewire Version |
|-----------------------|-----------------|------------------|
| 1.0.x                 | 10.x            | 3.x              |
| 1.5.x                 | 10.x - 11.x     | 3.x              |
| 2.0.x                 | 11.x+           | 3.x+             |

Always check the [compatibility documentation](https://docs.artisanpack-ui.com/cms-framework/compatibility) for the most up-to-date information.