<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Toast Position
    |--------------------------------------------------------------------------
    |
    | The default position where toasts will appear on the screen.
    | Supported: "top-right", "top-left", "bottom-right", "bottom-left",
    |            "top-center", "bottom-center"
    |
    */

    'position' => 'top-right',

    /*
    |--------------------------------------------------------------------------
    | Auto-dismiss Duration
    |--------------------------------------------------------------------------
    |
    | Default duration in milliseconds before a toast auto-dismisses.
    | Set to 0 for sticky toasts that require manual dismissal.
    |
    */

    'duration' => 3000,

    /*
    |--------------------------------------------------------------------------
    | Dismissal Behavior
    |--------------------------------------------------------------------------
    |
    | closable:       Show a close (×) button on each toast.
    | close_on_click: Allow clicking anywhere on the toast to dismiss it.
    | pause_on_hover: Pause auto-dismiss timer when hovering over a toast.
    |
    */

    'closable' => true,
    'close_on_click' => true,
    'pause_on_hover' => true,

    /*
    |--------------------------------------------------------------------------
    | Stacking & Queuing
    |--------------------------------------------------------------------------
    |
    | max_toasts: Maximum number of toasts visible at once (0 = unlimited).
    | queue:      If true, only show one toast at a time; queue the rest.
    |
    */

    'max_toasts' => 5,
    'queue' => false,

    /*
    |--------------------------------------------------------------------------
    | Duplicate Handling
    |--------------------------------------------------------------------------
    |
    | suppress_duplicates: If true, ignore new toasts with the same type+message.
    | replace_duplicates:  If true, replace existing duplicate with the new one.
    |
    */

    'suppress_duplicates' => false,
    'replace_duplicates' => false,

    /*
    |--------------------------------------------------------------------------
    | Icons & Progress Bar
    |--------------------------------------------------------------------------
    |
    | show_icon:         Show a type-specific icon in each toast.
    | show_progress_bar: Show an animated progress bar for auto-dismiss timing.
    |
    */

    'show_icon' => true,
    'show_progress_bar' => true,

    /*
    |--------------------------------------------------------------------------
    | Browser Event Name
    |--------------------------------------------------------------------------
    |
    | The name of the browser event dispatched by the backend.
    | The Alpine listener uses this name to capture incoming toasts.
    |
    */

    'event_name' => 'toast',

    /*
    |--------------------------------------------------------------------------
    | Toast Styles
    |--------------------------------------------------------------------------
    |
    | Per-type Tailwind CSS classes. Customize colors, backgrounds, and icons.
    | Each type supports: bg, border, text, icon_color, progress_bg
    |
    */

    'styles' => [
        'success' => [
            'bg'          => 'bg-emerald-50 dark:bg-emerald-950/80',
            'border'      => 'border-emerald-200 dark:border-emerald-800',
            'text'        => 'text-emerald-800 dark:text-emerald-200',
            'icon_color'  => 'text-emerald-500 dark:text-emerald-400',
            'progress_bg' => 'bg-emerald-500',
        ],
        'error' => [
            'bg'          => 'bg-red-50 dark:bg-red-950/80',
            'border'      => 'border-red-200 dark:border-red-800',
            'text'        => 'text-red-800 dark:text-red-200',
            'icon_color'  => 'text-red-500 dark:text-red-400',
            'progress_bg' => 'bg-red-500',
        ],
        'warning' => [
            'bg'          => 'bg-amber-50 dark:bg-amber-950/80',
            'border'      => 'border-amber-200 dark:border-amber-800',
            'text'        => 'text-amber-800 dark:text-amber-200',
            'icon_color'  => 'text-amber-500 dark:text-amber-400',
            'progress_bg' => 'bg-amber-500',
        ],
        'info' => [
            'bg'          => 'bg-sky-50 dark:bg-sky-950/80',
            'border'      => 'border-sky-200 dark:border-sky-800',
            'text'        => 'text-sky-800 dark:text-sky-200',
            'icon_color'  => 'text-sky-500 dark:text-sky-400',
            'progress_bg' => 'bg-sky-500',
        ],
    ],

];
