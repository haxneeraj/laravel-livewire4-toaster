<?php

namespace Haxneeraj\LivewireToaster\Tests;

use Haxneeraj\LivewireToaster\ToasterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ToasterServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Toast' => \Haxneeraj\LivewireToaster\Facades\Toast::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('toaster.position', 'top-right');
        $app['config']->set('toaster.duration', 3000);
    }
}
