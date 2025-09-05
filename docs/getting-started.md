---
title: Getting Started
---

# Getting Started

Welcome to the Livewire Starter Kit! This guide will help you understand the key concepts and get you building amazing applications quickly.

## Overview

The starter kit is built with modern technologies:

- **Laravel 12**: The latest version of the Laravel framework
- **Livewire 3**: Full-stack framework for Laravel
- **Volt**: Functional API for Livewire
- **ArtisanPack UI**: Modern UI components
- **Tailwind CSS 4**: Utility-first CSS framework

## First Steps

After [installation](installation), let's explore what's included and how to start building.

### Exploring the Dashboard

1. Start your development server:
```bash
composer dev
```

2. Visit your application at `http://localhost:8000`

3. You'll see the welcome page with options to register or login

### User Registration

1. Click "Register" to create a new account
2. Fill in the registration form
3. Check your email for verification (if email is configured)
4. Once verified, you'll be redirected to the dashboard

### Dashboard Features

The dashboard includes:
- User profile management
- Settings panel
- Navigation sidebar
- Dark/light mode toggle
- Responsive layout

## Project Structure

Understanding the project structure will help you navigate and extend the application:

```
├── app/
│   ├── Console/Commands/     # Custom Artisan commands
│   ├── Http/Controllers/     # HTTP controllers
│   ├── Livewire/            # Livewire components
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── resources/
│   ├── css/                 # Stylesheets and themes
│   ├── js/                  # JavaScript files
│   └── views/               # Blade templates
│       ├── components/      # Reusable components
│       └── livewire/        # Livewire component views
├── routes/
│   ├── auth.php            # Authentication routes
│   ├── console.php         # Console commands
│   └── web.php             # Web routes
└── docs/                   # Documentation (this directory)
```

## Key Concepts

### Livewire Components

Livewire allows you to build reactive interfaces without JavaScript. Components are located in:
- PHP logic: `app/Livewire/`
- Blade views: `resources/views/livewire/`

Example component structure:
```php
// app/Livewire/Counter.php
class Counter extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function render(): View
    {
        return view('livewire.counter');
    }
}
```

### Volt Integration

Volt provides a functional API for Livewire, allowing you to write components more concisely. Learn more in the [Livewire and Volt](components/livewire-volt) documentation.

### ArtisanPack UI Components

The starter kit includes ArtisanPack UI components for consistent design:

```blade
<x-artisanpack-button variant="primary" wire:click="save">
    Save Changes
</x-artisanpack-button>

<x-artisanpack-input wire:model="name" label="Name" />

<x-artisanpack-card>
    <x-artisanpack-card.header>
        <x-artisanpack-heading size="lg">Dashboard</x-artisanpack-heading>
    </x-artisanpack-card.header>
    <!-- Content -->
</x-artisanpack-card>
```

### Authentication System

The authentication system includes:
- User registration with email verification
- Login/logout functionality
- Password reset
- Profile management

Learn more in the [Authentication](authentication) guide.

## Building Your First Feature

Let's create a simple task management feature to demonstrate key concepts:

### 1. Create a Migration

```bash
php artisan make:migration create_tasks_table
```

Update the migration:
```php
Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->boolean('completed')->default(false);
    $table->timestamps();
});
```

Run the migration:
```bash
php artisan migrate
```

### 2. Create a Model

```bash
php artisan make:model Task
```

Update the model:
```php
class Task extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'completed'];
    
    protected $casts = [
        'completed' => 'boolean',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

### 3. Create a Livewire Component

```bash
php artisan make:livewire TaskManager
```

Update the component:
```php
class TaskManager extends Component
{
    public string $title = '';
    public string $description = '';
    
    public function addTask(): void
    {
        $this->validate([
            'title' => 'required|min:3',
            'description' => 'nullable|max:500',
        ]);
        
        auth()->user()->tasks()->create([
            'title' => $this->title,
            'description' => $this->description,
        ]);
        
        $this->reset('title', 'description');
    }
    
    public function render(): View
    {
        return view('livewire.task-manager', [
            'tasks' => auth()->user()->tasks()->latest()->get(),
        ]);
    }
}
```

### 4. Create the View

Update `resources/views/livewire/task-manager.blade.php`:
```blade
<div>
    <x-artisanpack-card>
        <x-artisanpack-card.header>
            <x-artisanpack-heading size="lg">Task Manager</x-artisanpack-heading>
        </x-artisanpack-card.header>
        
        <form wire:submit="addTask" class="space-y-4">
            <x-artisanpack-input 
                wire:model="title" 
                label="Task Title" 
                placeholder="Enter task title..."
                required 
            />
            
            <x-artisanpack-textarea 
                wire:model="description" 
                label="Description" 
                placeholder="Optional description..."
            />
            
            <x-artisanpack-button type="submit" variant="primary">
                Add Task
            </x-artisanpack-button>
        </form>
    </x-artisanpack-card>
    
    <div class="mt-8 space-y-4">
        @forelse($tasks as $task)
            <x-artisanpack-card>
                <h3 class="font-medium">{{ $task->title }}</h3>
                @if($task->description)
                    <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                @endif
            </x-artisanpack-card>
        @empty
            <p class="text-gray-500">No tasks yet. Add your first task above!</p>
        @endforelse
    </div>
</div>
```

### 5. Add to Navigation

Add the component to your dashboard or create a new route in `routes/web.php`:

```php
Route::middleware('auth')->group(function () {
    Route::get('/tasks', function () {
        return view('tasks');
    })->name('tasks');
});
```

Create `resources/views/tasks.blade.php`:
```blade
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:task-manager />
        </div>
    </div>
</x-app-layout>
```

## What's Next?

Now that you understand the basics, explore these topics:

1. **[Authentication](authentication)** - Deep dive into the auth system
2. **[Components](components)** - Learn about Livewire, Volt, and ArtisanPack UI
3. **[Configuration](configuration)** - Customize your application
4. **[Testing](testing)** - Write tests for your features
5. **[Deployment](deployment)** - Deploy your application

## Best Practices

### Code Organization
- Keep components focused and single-purpose
- Use proper validation for all form inputs
- Follow Laravel naming conventions

### Performance
- Use eager loading to avoid N+1 queries
- Implement proper caching strategies
- Optimize database queries

### Security
- Always validate user input
- Use proper authorization checks
- Keep dependencies updated

## Getting Help

- Check the [FAQ](faq) for common questions
- Review the [troubleshooting guide](troubleshooting)
- Visit the project repository for issues and discussions

Continue exploring the documentation to master all aspects of the starter kit!