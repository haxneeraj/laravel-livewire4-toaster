# Changelog

All notable changes to `livewire4-toaster` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-03-31

### Added
- Initial release
- `Toastable` trait for Livewire components (`success`, `error`, `info`, `warning`, `toast`)
- `Toast` facade for controllers and non-Livewire contexts
- `toast()` global helper function
- `InjectToasts` middleware for controller/redirect toast support
- Self-contained Alpine.js `<x-livewire-toaster::toast-hub />` Blade component
- `resources/js/toast.js` — global `window.Toast` JavaScript helper
- Configurable position, duration, icons, progress bar, close button
- Pause-on-hover auto-dismiss timer
- Queue mode and stacking with configurable max visible toasts
- Duplicate suppression and replacement
- Dark mode support with Tailwind CSS
- Smooth enter/exit transitions
- Publishable config (`toaster-config`) and views (`toaster-views`)
- Full test suite (ToastManager, ServiceProvider, InjectToasts middleware)
