<?php

namespace Haxneeraj\LivewireToaster\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Haxneeraj\LivewireToaster\ToastManager success(string $message, ?string $title = null, array $options = [])
 * @method static \Haxneeraj\LivewireToaster\ToastManager error(string $message, ?string $title = null, array $options = [])
 * @method static \Haxneeraj\LivewireToaster\ToastManager info(string $message, ?string $title = null, array $options = [])
 * @method static \Haxneeraj\LivewireToaster\ToastManager warning(string $message, ?string $title = null, array $options = [])
 * @method static \Haxneeraj\LivewireToaster\ToastManager toast(string $type, string $message, ?string $title = null, array $options = [])
 * @method static array toArray()
 * @method static array flush()
 * @method static void flashToSession()
 * @method static bool hasPending()
 * @method static int count()
 *
 * @see \Haxneeraj\LivewireToaster\ToastManager
 */
class Toast extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'toast';
    }
}
