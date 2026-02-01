---
title: Components
---

# Components

The Livewire Starter Kit includes three main types of components: Livewire components, Volt components, and ArtisanPack UI components.

## Overview

### Component Types

- **[Livewire Components](Components-Livewire-Volt)** - Server-side reactive components
- **[ArtisanPack UI Components](Components-Artisanpack-Ui)** - Pre-built UI components
- **[Layout Components](Components-Layouts)** - Application layout structure
- **[Custom Components](Components-Custom-Components)** - Application-specific components

### Key Features

- **Reactive Interfaces** without writing JavaScript
- **Server-Side Rendering** with real-time updates
- **Consistent Design** with ArtisanPack UI components
- **Reusable Components** for rapid development
- **Accessibility** built into all components
- **Dark Mode Support** across all components

## Livewire and Volt

Livewire provides the reactive functionality, while Volt offers a functional API for writing components more concisely.

### Basic Livewire Component

Traditional Livewire component structure:

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function decrement(): void
    {
        $this->count--;
    }

    public function render(): View
    {
        return view('livewire.counter');
    }
}
```

### Volt Component

The same component using Volt's functional API:

```php
<?php

use function Livewire\Volt\{state};

state(['count' => 0]);

$increment = fn() => $this->count++;
$decrement = fn() => $this->count--;

?>

<div>
    <h1>{{ $count }}</h1>
    
    <x-artisanpack-button wire:click="increment">+</x-artisanpack-button>
    <x-artisanpack-button wire:click="decrement">-</x-artisanpack-button>
</div>
```

## ArtisanPack UI Components

ArtisanPack UI provides a comprehensive set of pre-built components with consistent styling and accessibility features.

### Available Components

- **Form Elements**: Input, textarea, select, checkbox, radio
- **Navigation**: Navbar, breadcrumbs, dropdown
- **Display**: Button, badge, avatar, card
- **Feedback**: Modal, tooltip, callout
- **Layout**: Separator, heading, text

### Basic Usage

```blade
<!-- Form Components -->
<x-artisanpack-input label="Email" type="email" wire:model="email" />
<x-artisanpack-textarea label="Message" wire:model="message" />
<x-artisanpack-select label="Country" wire:model="country">
    <option value="us">United States</option>
    <option value="ca">Canada</option>
</x-artisanpack-select>

<!-- Buttons -->
<x-artisanpack-button variant="primary">Primary Button</x-artisanpack-button>
<x-artisanpack-button variant="secondary">Secondary Button</x-artisanpack-button>
<x-artisanpack-button variant="danger">Danger Button</x-artisanpack-button>

<!-- Cards -->
<x-artisanpack-card>
    <x-artisanpack-card.header>
        <x-artisanpack-heading size="lg">Card Title</x-artisanpack-heading>
    </x-artisanpack-card.header>
    
    <p>Card content goes here.</p>
    
    <x-artisanpack-card.footer>
        <x-artisanpack-button variant="primary">Action</x-artisanpack-button>
    </x-artisanpack-card.footer>
</x-artisanpack-card>
```

## Layout Components

The starter kit includes layout components for consistent application structure.

### App Layout

The main application layout:

```blade
<!-- resources/views/components/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Navigation -->
        <x-layouts.app.header />
        
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        
        <!-- Sidebar (if needed) -->
        <x-layouts.app.sidebar />
    </div>
</body>
</html>
```

### Auth Layout

Layout for authentication pages:

```blade
<!-- resources/views/components/layouts/auth.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <!-- Logo -->
        <div class="mb-6">
            <x-app-logo class="w-20 h-20" />
        </div>
        
        <!-- Content -->
        <div class="w-full sm:max-w-md px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
```

## Component Development

### Creating Components

Use Artisan commands to create new components:

```bash
# Create a new Livewire component
php artisan make:livewire PostsList

# Create a new Volt component
php artisan make:volt posts/create

# Create a new Blade component
php artisan make:component Alert
```

### Component Organization

Organize components by functionality:

```
resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── auth.blade.php
│   ├── forms/
│   │   ├── input.blade.php
│   │   └── select.blade.php
│   ├── ui/
│   │   ├── alert.blade.php
│   │   └── modal.blade.php
│   └── app-logo.blade.php
└── livewire/
    ├── auth/
    │   ├── login.blade.php
    │   └── register.blade.php
    └── settings/
        ├── profile.blade.php
        └── password.blade.php
```

### Component Best Practices

1. **Keep components focused** on a single responsibility
2. **Use proper naming** conventions (PascalCase for classes)
3. **Implement proper validation** for all inputs
4. **Handle loading states** appropriately
5. **Make components accessible** with ARIA attributes
6. **Support dark mode** in styling

## Advanced Features

### Real-Time Updates

Implement real-time features with Livewire:

```php
<?php

use function Livewire\Volt\{state, on};

state(['messages' => []]);

on(['MessageSent' => function ($message) {
    $this->messages[] = $message;
}]);

$sendMessage = function () {
    // Send message logic
    
    // Broadcast to other users
    broadcast(new MessageSent($message))->toOthers();
};

?>

<div>
    @foreach($messages as $message)
        <div class="message">{{ $message }}</div>
    @endforeach
    
    <form wire:submit="sendMessage">
        <x-artisanpack-input wire:model="newMessage" placeholder="Type a message..." />
        <x-artisanpack-button type="submit">Send</x-artisanpack-button>
    </form>
</div>
```

### File Uploads

Handle file uploads with Livewire:

```php
<?php

use Livewire\WithFileUploads;
use function Livewire\Volt\{uses, state, rules};

uses([WithFileUploads::class]);

state(['photo' => null]);

rules(['photo' => 'image|max:1024']);

$save = function () {
    $this->validate();
    
    $path = $this->photo->store('photos', 'public');
    
    // Save to database or process further
};

?>

<div>
    <form wire:submit="save">
        <div>
            <x-artisanpack-field label="Upload Photo">
                <input type="file" wire:model="photo" />
            </x-artisanpack-field>
            
            @if($photo)
                <div class="mt-2">
                    <img src="{{ $photo->temporaryUrl() }}" class="w-32 h-32 object-cover rounded" />
                </div>
            @endif
        </div>
        
        <x-artisanpack-button type="submit" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="save">Save Photo</span>
            <span wire:loading wire:target="save">Uploading...</span>
        </x-artisanpack-button>
    </form>
</div>
```

### Pagination

Implement pagination with Livewire:

```php
<?php

use Livewire\WithPagination;
use function Livewire\Volt\{uses, state, computed};

uses([WithPagination::class]);

state(['search' => '']);

$posts = computed(function () {
    return Post::where('title', 'like', '%' . $this->search . '%')
        ->paginate(10);
});

$updatedSearch = function () {
    $this->resetPage();
};

?>

<div>
    <x-artisanpack-input 
        wire:model.live.debounce.300ms="search" 
        placeholder="Search posts..."
    />
    
    <div class="mt-6 space-y-4">
        @foreach($this->posts as $post)
            <x-artisanpack-card>
                <h3 class="font-medium">{{ $post->title }}</h3>
                <p class="text-gray-600">{{ $post->excerpt }}</p>
            </x-artisanpack-card>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $this->posts->links() }}
    </div>
</div>
```

## Testing Components

### Livewire Component Tests

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
        ->call('submit')
        ->assertHasErrors('email');
});
```

### Component Integration Tests

```php
test('user can create post', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->get('/posts/create')
        ->assertSeeLivewire('create-post');
        
    Livewire::test('create-post')
        ->set('title', 'Test Post')
        ->set('content', 'This is a test post.')
        ->call('save')
        ->assertRedirect('/posts');
        
    $this->assertDatabaseHas('posts', [
        'title' => 'Test Post',
        'user_id' => $user->id,
    ]);
});
```

## Component Guides

Explore detailed guides for each component type:

- **[Livewire and Volt](Components-Livewire-Volt)** - Reactive components with server-side logic
- **[ArtisanPack UI Components](Components-Artisanpack-Ui)** - Pre-built UI components and styling
- **[Layout Components](Components-Layouts)** - Application layout structure
- **[Custom Components](Components-Custom-Components)** - Building custom components
- **[Form Components](Components-Forms)** - Form handling and validation
- **[Data Components](Components-Data)** - Tables, lists, and data display
- **[Interactive Components](Components-Interactive)** - Modals, dropdowns, and interactions

## Performance Optimization

### Component Optimization

1. **Use wire:key** in loops to prevent re-rendering issues
2. **Implement lazy loading** for expensive operations
3. **Defer updates** when real-time isn't necessary
4. **Use computed properties** for expensive calculations
5. **Optimize database queries** to prevent N+1 problems

### Loading States

Provide feedback during operations:

```blade
<x-artisanpack-button wire:click="save" wire:loading.attr="disabled">
    <span wire:loading.remove wire:target="save">Save</span>
    <span wire:loading wire:target="save">Saving...</span>
</x-artisanpack-button>

<div wire:loading wire:target="save" class="text-sm text-gray-500">
    Processing your request...
</div>
```

## Next Steps

- Learn about [Livewire and Volt](Components-Livewire-Volt) in detail
- Explore [ArtisanPack UI Components](Components-Artisanpack-Ui) documentation
- Review [Layout Components](Components-Layouts) structure
- Check [Testing](Testing) component best practices