<?php

namespace Haxneeraj\LivewireToaster;

use Illuminate\Support\ServiceProvider;

class ToasterServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/toaster.php',
            'toaster'
        );

        $this->app->singleton(ToastManager::class, function ($app) {
            return new ToastManager($app['config']->get('toaster', []));
        });

        $this->app->alias(ToastManager::class, 'toast');
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'livewire-toaster'
        );

        $this->configurePublishing();
    }

    /**
     * Configure the publishable resources offered by the package.
     */
    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Config
        $this->publishes([
            __DIR__ . '/../config/toaster.php' => config_path('toaster.php'),
        ], 'toaster-config');

        // Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/livewire-toaster'),
        ], 'toaster-views');
    }
}
