---
title: Contributing
---

# Contributing

Thank you for considering contributing to the Livewire Starter Kit! This guide outlines how to contribute effectively to the project.

## Code of Conduct

This project adheres to a Code of Conduct. By participating, you are expected to uphold this code:

- **Be respectful** of differing viewpoints and experiences
- **Be welcoming** to newcomers and help them get started
- **Be constructive** in your feedback and criticism
- **Focus on what is best** for the community and the project
- **Show empathy** towards other community members

## Getting Started

### Prerequisites

Before contributing, ensure you have:

- **PHP 8.2+** installed locally
- **Composer** for dependency management
- **Node.js 18+** and NPM for frontend assets
- **Git** for version control
- **Basic Laravel knowledge** and familiarity with Livewire

### Development Setup

1. **Fork the repository** on GitHub

2. **Clone your fork** locally:
```bash
git clone https://github.com/your-username/livewire-starter-kit.git
cd livewire-starter-kit
```

3. **Install dependencies**:
```bash
composer install
npm install
```

4. **Set up environment**:
```bash
cp .env.example .env
php artisan key:generate
```

5. **Set up database**:
```bash
touch database/database.sqlite
php artisan migrate
```

6. **Start development server**:
```bash
composer dev
```

## Ways to Contribute

### Reporting Issues

When reporting issues, please:

1. **Check existing issues** to avoid duplicates
2. **Use the issue template** if available
3. **Provide clear reproduction steps**
4. **Include environment information**
5. **Add screenshots** if applicable

#### Issue Template

```markdown
**Bug Description**
A clear description of the bug.

**Steps to Reproduce**
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected Behavior**
What you expected to happen.

**Screenshots**
Add screenshots to help explain your problem.

**Environment**
- PHP Version:
- Laravel Version:
- Livewire Version:
- Browser:
- OS:
```

### Suggesting Features

For feature requests:

1. **Check if the feature already exists** or is planned
2. **Explain the use case** and benefits
3. **Provide implementation ideas** if possible
4. **Consider backwards compatibility**

### Code Contributions

#### Pull Request Process

1. **Create a feature branch** from `main`:
```bash
git checkout -b feature/your-feature-name
```

2. **Make your changes** following our coding standards

3. **Write or update tests** for your changes

4. **Update documentation** if needed

5. **Run the test suite**:
```bash
php artisan test
```

6. **Run code formatting**:
```bash
vendor/bin/pint
```

7. **Commit your changes**:
```bash
git commit -m "Add feature: your feature description"
```

8. **Push to your fork**:
```bash
git push origin feature/your-feature-name
```

9. **Create a Pull Request** on GitHub

#### Pull Request Guidelines

- **Use descriptive titles** that explain what the PR does
- **Reference related issues** using keywords like "Fixes #123"
- **Provide a detailed description** of changes made
- **Include screenshots** for UI changes
- **Keep PRs focused** on a single feature or fix
- **Update documentation** for new features

### Documentation Contributions

Help improve the documentation by:

- **Fixing typos** and grammatical errors
- **Adding examples** and code samples
- **Improving clarity** of explanations
- **Adding missing documentation** for features
- **Translating documentation** to other languages

## Development Guidelines

### Coding Standards

#### PHP Standards

Follow Laravel and PHP best practices:

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class ExampleComponent extends Component
{
    public string $title = '';
    
    public array $items = [];

    public function mount(): void
    {
        $this->loadItems();
    }

    public function addItem(string $name): void
    {
        $this->validate(['name' => 'required|min:3']);
        
        $this->items[] = [
            'id' => uniqid(),
            'name' => $name,
            'created_at' => now(),
        ];
    }

    public function render(): View
    {
        return view('livewire.example-component');
    }

    private function loadItems(): void
    {
        // Implementation
    }
}
```

#### Key Principles

1. **Use type declarations** for all method parameters and return types
2. **Follow PSR-12** coding standards
3. **Use descriptive variable names** and method names
4. **Keep methods focused** on single responsibilities
5. **Add PHPDoc blocks** for complex methods
6. **Use early returns** to reduce nesting

#### Blade Templates

```blade
<div>
    @if($items->count() > 0)
        <div class="grid gap-4">
            @foreach($items as $item)
                <x-artisanpack-card wire:key="item-{{ $item->id }}">
                    <h3>{{ $item->name }}</h3>
                    <p>{{ $item->created_at->diffForHumans() }}</p>
                </x-artisanpack-card>
            @endforeach
        </div>
    @else
        <x-artisanpack-callout variant="info">
            No items found.
        </x-artisanpack-callout>
    @endif
</div>
```

### Testing Standards

#### Write Comprehensive Tests

Every contribution should include appropriate tests:

```php
<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('user can add item to list', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user);
    
    Volt::test('item-manager')
        ->set('newItemName', 'Test Item')
        ->call('addItem')
        ->assertHasNoErrors()
        ->assertSee('Test Item');
});

test('adding item requires valid name', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user);
    
    Volt::test('item-manager')
        ->set('newItemName', '')
        ->call('addItem')
        ->assertHasErrors('newItemName');
});
```

#### Test Categories

- **Feature Tests**: Test complete user workflows
- **Unit Tests**: Test individual methods and classes
- **Component Tests**: Test Livewire components
- **Integration Tests**: Test component interactions

### Git Workflow

#### Commit Messages

Use conventional commit format:

```
type(scope): description

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code formatting changes
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**
```
feat(auth): add password reset functionality
fix(components): resolve form validation issue
docs(readme): update installation instructions
test(auth): add login component tests
```

#### Branch Naming

Use descriptive branch names:

```
feature/user-profile-management
fix/login-validation-error
docs/authentication-guide
refactor/component-structure
```

## Code Review Process

### Review Checklist

Reviewers should check:

- [ ] **Code follows project standards** and conventions
- [ ] **Tests are included** and passing
- [ ] **Documentation is updated** if necessary
- [ ] **No breaking changes** without proper migration path
- [ ] **Security considerations** are addressed
- [ ] **Performance implications** are considered
- [ ] **Accessibility requirements** are met

### Reviewer Guidelines

- **Be constructive** and respectful in feedback
- **Explain the "why"** behind suggestions
- **Acknowledge good code** and improvements
- **Focus on the code**, not the person
- **Ask questions** to understand the approach
- **Suggest alternatives** when appropriate

### Author Guidelines

- **Respond promptly** to review feedback
- **Be open to suggestions** and different approaches
- **Ask for clarification** if feedback is unclear
- **Make requested changes** or explain why they're not needed
- **Thank reviewers** for their time and effort

## Release Process

### Versioning

The project follows [Semantic Versioning](https://semver.org/):

- **MAJOR** version for incompatible API changes
- **MINOR** version for backwards-compatible functionality
- **PATCH** version for backwards-compatible bug fixes

### Release Checklist

Before releasing:

- [ ] All tests pass
- [ ] Documentation is updated
- [ ] CHANGELOG.md is updated
- [ ] Version numbers are bumped
- [ ] Security audit is completed
- [ ] Performance benchmarks are acceptable

## Community Guidelines

### Communication Channels

- **GitHub Issues**: Bug reports and feature requests
- **GitHub Discussions**: General discussions and questions
- **Pull Requests**: Code contributions and reviews

### Getting Help

If you need help:

1. **Check the documentation** first
2. **Search existing issues** and discussions
3. **Ask in GitHub Discussions** for general questions
4. **Create an issue** for bugs or feature requests

### Recognition

Contributors are recognized through:

- **GitHub Contributors page**
- **CHANGELOG.md** mentions
- **Release notes** acknowledgments
- **Community highlights** in discussions

## Development Tools

### Recommended IDE Setup

**VS Code Extensions:**
- PHP Intelephense
- Laravel Extension Pack
- Tailwind CSS IntelliSense
- Blade Formatter
- GitLens

**PHPStorm Plugins:**
- Laravel Plugin
- Livewire Plugin
- Tailwind CSS Support

### Code Quality Tools

#### Laravel Pint (Code Formatting)

```bash
# Format all files
vendor/bin/pint

# Format specific directory
vendor/bin/pint app/

# Check formatting without fixing
vendor/bin/pint --test
```

#### PHPStan (Static Analysis)

```bash
# Run static analysis
vendor/bin/phpstan analyse
```

#### Pest (Testing)

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=UserCanLogin

# Run with coverage
php artisan test --coverage
```

## Security

### Reporting Security Issues

**Do not** report security vulnerabilities through public GitHub issues.

Instead:
1. **Email security issues** to security@yourproject.com
2. **Include detailed information** about the vulnerability
3. **Provide steps to reproduce** if possible
4. **Allow reasonable time** for response and fixes

### Security Best Practices

When contributing:

- **Validate all inputs** properly
- **Use parameterized queries** for database operations
- **Implement proper authorization** checks
- **Avoid exposing sensitive information** in logs or responses
- **Follow OWASP guidelines** for web application security

## Performance Guidelines

### Frontend Performance

- **Optimize images** and assets
- **Minimize JavaScript** usage where possible
- **Use CSS efficiently** with Tailwind's utility classes
- **Implement proper caching** strategies

### Backend Performance

- **Optimize database queries** to avoid N+1 problems
- **Use eager loading** for relationships
- **Implement proper indexing** on database columns
- **Cache expensive operations** appropriately

### Livewire Performance

- **Use wire:key** for dynamic lists
- **Minimize component re-renders** with targeted updates
- **Defer heavy operations** when possible
- **Optimize component lifecycle** methods

## Accessibility

Ensure all contributions maintain accessibility:

- **Use semantic HTML** elements
- **Provide proper ARIA labels** and roles
- **Ensure keyboard navigation** works properly
- **Maintain sufficient color contrast**
- **Test with screen readers** when possible

## Internationalization

When adding new features:

- **Use Laravel's localization** features for user-facing text
- **Provide translation keys** for all strings
- **Consider RTL language support** for layouts
- **Test with different locales**

## Final Notes

### License

By contributing, you agree that your contributions will be licensed under the same license as the project (MIT License).

### Questions?

If you have questions about contributing:

- **Check this guide** first
- **Search existing discussions** and issues
- **Ask in GitHub Discussions**
- **Reach out to maintainers** if needed

Thank you for helping make the Livewire Starter Kit better for everyone! 🚀