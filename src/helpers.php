<?php

use Haxneeraj\LivewireToaster\ToastManager;

if (! function_exists('toast')) {
    /**
     * Get the toast manager instance or dispatch a quick toast.
     *
     * @param  string|null  $type     Toast type (success, error, info, warning)
     * @param  string|null  $message  Toast message
     * @param  string|null  $title    Optional title
     * @param  array        $options  Additional options
     * @return \Haxneeraj\LivewireToaster\ToastManager
     */
    function toast(?string $type = null, ?string $message = null, ?string $title = null, array $options = []): ToastManager
    {
        /** @var ToastManager $manager */
        $manager = app('toast');

        if ($type !== null && $message !== null) {
            $manager->toast($type, $message, $title, $options);
        }

        return $manager;
    }
}
