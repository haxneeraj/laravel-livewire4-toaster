<?php

namespace Haxneeraj\LivewireToaster\Traits;

use Illuminate\Support\Str;

trait Toastable
{
    /**
     * Dispatch a success toast notification.
     */
    public function success(string $message, ?string $title = null, array $options = []): void
    {
        $this->toast('success', $message, $title, $options);
    }

    /**
     * Dispatch an error toast notification.
     */
    public function error(string $message, ?string $title = null, array $options = []): void
    {
        $this->toast('error', $message, $title, $options);
    }

    /**
     * Dispatch an info toast notification.
     */
    public function info(string $message, ?string $title = null, array $options = []): void
    {
        $this->toast('info', $message, $title, $options);
    }

    /**
     * Dispatch a warning toast notification.
     */
    public function warning(string $message, ?string $title = null, array $options = []): void
    {
        $this->toast('warning', $message, $title, $options);
    }

    /**
     * Dispatch a toast notification via Livewire browser event.
     *
     * This method uses Livewire's native dispatch() to send a browser event
     * that the Alpine.js toast-hub component listens for.
     *
     * In Livewire 4, dispatch() sends events as browser CustomEvents.
     * We pass the entire payload as named arguments that appear in $event.detail.
     */
    public function toast(string $type, string $message, ?string $title = null, array $options = []): void
    {
        $eventName = config('toaster.event_name', 'toast');

        $payload = array_merge([
            'id'       => Str::uuid()->toString(),
            'type'     => $type,
            'message'  => $message,
            'title'    => $title,
            'duration' => config('toaster.duration', 3000),
            'position' => config('toaster.position', 'top-right'),
        ], $options);

        // Livewire 4: dispatch as named params — they land in $event.detail
        $this->dispatch($eventName, toast: $payload);
    }
}
