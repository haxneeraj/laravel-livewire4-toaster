# 🍞 Livewire4 Toaster

[![Latest Version on Packagist](https://img.shields.io/packagist/v/haxneeraj/livewire4-toaster.svg?style=flat-square)](https://packagist.org/packages/haxneeraj/livewire4-toaster)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-8892BF.svg?style=flat-square)](https://php.net)

**Elegant toast notifications for Laravel Livewire 4 + Alpine.js** — event-driven, configurable, zero external dependencies.

![Toast Preview](https://img.shields.io/badge/🟢_Success-✓-emerald?style=for-the-badge) ![Toast Preview](https://img.shields.io/badge/🔴_Error-✗-red?style=for-the-badge) ![Toast Preview](https://img.shields.io/badge/🟡_Warning-⚠-amber?style=for-the-badge) ![Toast Preview](https://img.shields.io/badge/🔵_Info-ℹ-sky?style=for-the-badge)

---

## ✨ Features

- **Trait-based API** — `$this->success('Saved!')` in any Livewire component
- **Facade & Helper** — `Toast::error('Failed!')` or `toast('success', 'Done')` from controllers
- **Event-driven** — Livewire 4 `dispatch()` → Alpine `x-on:toast.window`
- **Auto-dismiss** with animated progress bar & pause-on-hover
- **Queue mode** — show one toast at a time with automatic queue
- **Duplicate handling** — suppress or replace identical toasts
- **Configurable positions** — top-right, top-left, bottom-right, bottom-left, top-center, bottom-center
- **Dark mode** ready with Tailwind CSS
- **Accessible** — `role="status"`, `aria-live="polite"`
- **Redirect support** — flash toasts to session, inject on next page load
- **JavaScript API** — `window.Toast.success('Hello!')` for frontend-only toasts
- **Publishable** config & views — full control when you need it
- **Zero external dependencies** — no toastr.js, no notyf, just Alpine.js

---

## 📦 Installation

### 1. Install via Composer

```bash
composer require haxneeraj/livewire4-toaster
```

The package auto-discovers its service provider and facade.

### 2. Publish Config (Optional)

```bash
php artisan vendor:publish --tag=toaster-config
```

This creates `config/toaster.php` where you can customize everything.

### 3. Add the Toast Hub to Your Layout

Place the toast-hub component in your main layout file, typically before `</body>`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    {{ $slot }}

    {{-- Toast notification container --}}
    <x-livewire-toaster::toast-hub />

    @livewireScripts
</body>
</html>
```

That's it! You're ready to toast. 🍞

---

## 🚀 Usage

### In Livewire Components (Toastable Trait)

The `Toastable` trait is the primary API for Livewire components:

```php
<?php

namespace App\Livewire;

use Haxneeraj\LivewireToaster\Traits\Toastable;
use Livewire\Component;

class ProfileForm extends Component
{
    use Toastable;

    public string $name = '';

    public function save()
    {
        // ... validate and save ...

        $this->success('Profile updated successfully!', 'Saved');
    }

    public function delete()
    {
        // ... delete logic ...

        $this->error('Profile has been deleted.', 'Deleted');
    }

    public function archive()
    {
        $this->warning('Profile archived. You can restore it within 30 days.', 'Archived', [
            'duration' => 5000,
        ]);
    }

    public function notify()
    {
        $this->info('You have 3 unread messages.');
    }

    public function customToast()
    {
        $this->toast('success', 'Custom positioned toast!', null, [
            'position' => 'bottom-left',
            'duration' => 0, // sticky — manual dismiss only
        ]);
    }

    public function render()
    {
        return view('livewire.profile-form');
    }
}
```

### In Controllers (Toast Facade)

Use the `Toast` facade for controller-based workflows:

```php
<?php

namespace App\Http\Controllers;

use Haxneeraj\LivewireToaster\Facades\Toast;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        // ... create user ...

        Toast::success('User created successfully!', 'Welcome');

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        Toast::error('User has been removed.', 'Deleted');

        return redirect()->route('users.index');
    }
}
```

> **Note:** When using the facade with redirects, register the `InjectToasts` middleware (see [Middleware Setup](#middleware-setup)).

### Global Helper Function

```php
// Quick toast
toast('success', 'Item saved!');

// Get the manager and chain
toast()->success('First toast')->error('Second toast');

// With options
toast('warning', 'Low disk space!', 'Warning', ['duration' => 10000]);
```

### From JavaScript

Include `resources/js/toast.js` in your build, or use the global `window.Toast` object (available when the hub is loaded):

```javascript
// These work anywhere in your JS
Toast.success('Saved!');
Toast.error('Something went wrong.', 'Error');
Toast.info('FYI...', null, { duration: 5000 });
Toast.warning('Careful!', 'Warning');
Toast.show('success', 'Custom type toast');
```

Or dispatch a raw event:

```javascript
window.dispatchEvent(new CustomEvent('toast', {
    detail: {
        type: 'success',
        message: 'Hello from JS!',
        title: 'Custom Event',
        duration: 3000,
    }
}));
```

### In Alpine.js

```html
<button
    type="button"
    x-data
    x-on:click="$dispatch('toast', {
        type: 'success',
        message: 'Button clicked!',
        title: 'Action'
    })"
>
    Click me
</button>
```

---

## ⚙️ Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=toaster-config
```

### Configuration Reference

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `position` | `string` | `"top-right"` | Toast position. Options: `top-right`, `top-left`, `bottom-right`, `bottom-left`, `top-center`, `bottom-center` |
| `duration` | `int` | `3000` | Auto-dismiss time in ms. `0` = sticky (manual dismiss only) |
| `closable` | `bool` | `true` | Show close (×) button |
| `close_on_click` | `bool` | `true` | Click anywhere on toast to dismiss |
| `pause_on_hover` | `bool` | `true` | Pause auto-dismiss when hovering |
| `max_toasts` | `int` | `5` | Max visible toasts. `0` = unlimited |
| `queue` | `bool` | `false` | Queue mode: show one at a time |
| `suppress_duplicates` | `bool` | `false` | Ignore duplicate type+message |
| `replace_duplicates` | `bool` | `false` | Replace existing duplicate |
| `show_icon` | `bool` | `true` | Show type-specific icon |
| `show_progress_bar` | `bool` | `true` | Show animated progress bar |
| `event_name` | `string` | `"toast"` | Browser event name |
| `styles` | `array` | *see below* | Per-type Tailwind CSS classes |

### Style Configuration

Each toast type has customizable Tailwind classes:

```php
'styles' => [
    'success' => [
        'bg'          => 'bg-emerald-50 dark:bg-emerald-950/80',
        'border'      => 'border-emerald-200 dark:border-emerald-800',
        'text'        => 'text-emerald-800 dark:text-emerald-200',
        'icon_color'  => 'text-emerald-500 dark:text-emerald-400',
        'progress_bg' => 'bg-emerald-500',
    ],
    // ... error, warning, info
],
```

---

## 🔧 Middleware Setup

To use the `Toast` facade or `toast()` helper in controllers (especially with redirects), register the `InjectToasts` middleware.

### Laravel 11+ (bootstrap/app.php)

```php
use Haxneeraj\LivewireToaster\Http\Middleware\InjectToasts;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            InjectToasts::class,
        ]);
    })
    ->create();
```

### Laravel 10 (app/Http/Kernel.php)

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware ...
        \Haxneeraj\LivewireToaster\Http\Middleware\InjectToasts::class,
    ],
];
```

The middleware:
1. Collects pending toasts from `ToastManager` and session flash
2. Injects a `<script>` tag before `</body>` that dispatches `window` events
3. The Alpine toast-hub catches these events and renders the toasts

---

## 🎨 Customizing Views

Publish the views to fully customize the toast markup:

```bash
php artisan vendor:publish --tag=toaster-views
```

This copies the Blade component to `resources/views/vendor/livewire-toaster/`.

### Tailwind CSS Purge

Add the package views to your Tailwind content paths:

```js
// tailwind.config.js
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './vendor/haxneeraj/livewire4-toaster/resources/views/**/*.blade.php',
        // ...
    ],
};
```

---

## 🧪 Testing

### Running Tests

```bash
composer install
vendor/bin/phpunit
```

### Testing Your Toasts

When testing Livewire components that use the `Toastable` trait:

```php
use Livewire\Livewire;

public function test_profile_save_shows_success_toast()
{
    Livewire::test(ProfileForm::class)
        ->set('name', 'John')
        ->call('save')
        ->assertDispatched('toast');
}
```

When testing controllers that use the facade:

```php
use Haxneeraj\LivewireToaster\Facades\Toast;
use Haxneeraj\LivewireToaster\ToastManager;

public function test_user_creation_flashes_toast()
{
    $this->post('/users', ['name' => 'John', 'email' => 'john@example.com']);

    $manager = app(ToastManager::class);
    // Toasts are flushed by middleware, so check session for redirect tests
    $this->assertNotNull(session('livewire_toaster'));
}
```

---

## 📖 API Reference

### Toastable Trait Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `success()` | `success(string $message, ?string $title = null, array $options = [])` | Green success toast |
| `error()` | `error(string $message, ?string $title = null, array $options = [])` | Red error toast |
| `info()` | `info(string $message, ?string $title = null, array $options = [])` | Blue info toast |
| `warning()` | `warning(string $message, ?string $title = null, array $options = [])` | Amber warning toast |
| `toast()` | `toast(string $type, string $message, ?string $title = null, array $options = [])` | Generic toast |

### Toast Facade / Manager Methods

Same methods as above, plus:

| Method | Description |
|--------|-------------|
| `toArray()` | Get all pending toasts |
| `flush()` | Get and clear pending toasts |
| `flashToSession()` | Flash toasts to session (for redirects) |
| `hasPending()` | Check if toasts are queued |
| `count()` | Count pending toasts |

### Per-Toast Options

Pass as the `$options` array:

```php
$this->success('Done!', 'OK', [
    'duration' => 5000,      // Override duration (ms)
    'position' => 'top-left', // Override position
]);
```

---

## 📁 Package Structure

```
livewire4-toaster/
├── config/
│   └── toaster.php              # Published config
├── resources/
│   ├── js/
│   │   └── toast.js             # JS helper (window.Toast)
│   └── views/
│       └── components/
│           └── toast-hub.blade.php  # Alpine.js toast container
├── src/
│   ├── Facades/
│   │   └── Toast.php            # Laravel facade
│   ├── Http/
│   │   └── Middleware/
│   │       └── InjectToasts.php # Middleware for controller support
│   ├── Traits/
│   │   └── Toastable.php        # Livewire trait
│   ├── ToastManager.php         # Core toast collector
│   ├── ToasterServiceProvider.php # Service provider
│   └── helpers.php              # Global toast() helper
├── tests/
│   ├── Unit/
│   │   ├── InjectToastsMiddlewareTest.php
│   │   ├── ServiceProviderTest.php
│   │   └── ToastManagerTest.php
│   └── TestCase.php
├── CHANGELOG.md
├── LICENSE
├── README.md
├── composer.json
└── phpunit.xml
```

---

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing`)
5. Open a Pull Request

Please ensure tests pass (`vendor/bin/phpunit`) and follow PSR-12 coding standards.

---

## 📝 License

The MIT License (MIT). See [LICENSE](LICENSE) for details.

---

## 🙏 Credits

- **[Neeraj Saini (HaxNeeraj)](https://haxneeraj.com)** — Creator & Maintainer
  - [![GitHub](https://img.shields.io/badge/GitHub-haxneeraj-181717?style=flat-square&logo=github)](https://github.com/haxneeraj)
  - [![LinkedIn](https://img.shields.io/badge/LinkedIn-hax--neeraj-0A66C2?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/hax-neeraj/)
- Built for the [TALL Stack](https://tallstack.dev/) community
