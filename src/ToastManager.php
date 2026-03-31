<?php

namespace Haxneeraj\LivewireToaster;

use Illuminate\Support\Str;

class ToastManager
{
    /**
     * Collected toasts waiting to be dispatched.
     *
     * @var array<int, array<string, mixed>>
     */
    protected array $toasts = [];

    /**
     * Package configuration.
     *
     * @var array<string, mixed>
     */
    protected array $config;

    /**
     * Create a new ToastManager instance.
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Dispatch a success toast.
     */
    public function success(string $message, ?string $title = null, array $options = []): static
    {
        return $this->toast('success', $message, $title, $options);
    }

    /**
     * Dispatch an error toast.
     */
    public function error(string $message, ?string $title = null, array $options = []): static
    {
        return $this->toast('error', $message, $title, $options);
    }

    /**
     * Dispatch an info toast.
     */
    public function info(string $message, ?string $title = null, array $options = []): static
    {
        return $this->toast('info', $message, $title, $options);
    }

    /**
     * Dispatch a warning toast.
     */
    public function warning(string $message, ?string $title = null, array $options = []): static
    {
        return $this->toast('warning', $message, $title, $options);
    }

    /**
     * Create a toast with the given type.
     */
    public function toast(string $type, string $message, ?string $title = null, array $options = []): static
    {
        $payload = array_merge([
            'id'       => Str::uuid()->toString(),
            'type'     => $type,
            'message'  => $message,
            'title'    => $title,
            'duration' => $this->config['duration'] ?? 3000,
            'position' => $this->config['position'] ?? 'top-right',
        ], $options);

        $this->toasts[] = $payload;

        return $this;
    }

    /**
     * Get all pending toasts.
     *
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return $this->toasts;
    }

    /**
     * Get pending toasts and clear the queue.
     *
     * @return array<int, array<string, mixed>>
     */
    public function flush(): array
    {
        $toasts = $this->toasts;
        $this->toasts = [];

        return $toasts;
    }

    /**
     * Flash toasts to session for redirect scenarios.
     */
    public function flashToSession(): void
    {
        if (empty($this->toasts)) {
            return;
        }

        $existing = session()->get('livewire_toaster', []);
        session()->flash('livewire_toaster', array_merge($existing, $this->toasts));
        $this->toasts = [];
    }

    /**
     * Determine if there are pending toasts.
     */
    public function hasPending(): bool
    {
        return ! empty($this->toasts);
    }

    /**
     * Get the count of pending toasts.
     */
    public function count(): int
    {
        return count($this->toasts);
    }

    /**
     * Get the event name from config.
     */
    public function eventName(): string
    {
        return $this->config['event_name'] ?? 'toast';
    }
}
