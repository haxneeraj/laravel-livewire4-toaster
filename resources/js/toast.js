/**
 * Livewire4 Toaster — Alpine.js Plugin & Global Helper
 *
 * This file provides:
 * 1. An Alpine.js plugin for manual registration (optional)
 * 2. A global `window.Toast` helper for dispatching toasts from JavaScript
 *
 * Usage (Alpine Plugin — optional, the Blade component auto-registers):
 *   import Toast from 'livewire4-toaster/resources/js/toast';
 *   Alpine.plugin(Toast);
 *
 * Usage (Global Helper — works anywhere in JS):
 *   Toast.success('Profile saved!');
 *   Toast.error('Something went wrong', 'Error');
 *   Toast.info('FYI...', null, { duration: 5000 });
 *   Toast.warning('Low stock', 'Warning');
 */

(function () {
    'use strict';

    const DEFAULT_EVENT_NAME = 'toast';

    /**
     * Generate a unique toast ID.
     */
    function generateId() {
        return 'toast-' + Date.now() + '-' + Math.random().toString(36).substring(2, 9);
    }

    /**
     * Dispatch a toast event on the window.
     *
     * @param {string} type     - Toast type: success, error, info, warning
     * @param {string} message  - Toast message
     * @param {string|null} title - Optional title
     * @param {object} options  - Additional options (duration, position, etc.)
     */
    function dispatchToast(type, message, title = null, options = {}) {
        const eventName = options.eventName || DEFAULT_EVENT_NAME;
        delete options.eventName;

        const payload = Object.assign({
            id: generateId(),
            type: type,
            message: message,
            title: title,
        }, options);

        window.dispatchEvent(new CustomEvent(eventName, { detail: payload }));
    }

    /**
     * Global Toast helper object.
     */
    const Toast = {
        /**
         * Show a success toast.
         * @param {string} message
         * @param {string|null} title
         * @param {object} options
         */
        success(message, title = null, options = {}) {
            dispatchToast('success', message, title, options);
        },

        /**
         * Show an error toast.
         * @param {string} message
         * @param {string|null} title
         * @param {object} options
         */
        error(message, title = null, options = {}) {
            dispatchToast('error', message, title, options);
        },

        /**
         * Show an info toast.
         * @param {string} message
         * @param {string|null} title
         * @param {object} options
         */
        info(message, title = null, options = {}) {
            dispatchToast('info', message, title, options);
        },

        /**
         * Show a warning toast.
         * @param {string} message
         * @param {string|null} title
         * @param {object} options
         */
        warning(message, title = null, options = {}) {
            dispatchToast('warning', message, title, options);
        },

        /**
         * Show a toast with a custom type.
         * @param {string} type
         * @param {string} message
         * @param {string|null} title
         * @param {object} options
         */
        show(type, message, title = null, options = {}) {
            dispatchToast(type, message, title, options);
        },
    };

    // Expose globally
    window.Toast = Toast;

    // Export as Alpine plugin (optional registration)
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = function (Alpine) {
            // The Alpine data component is already registered in the Blade view.
            // This plugin just makes window.Toast available for programmatic use.
            Alpine.magic('toast', () => Toast);
        };
        module.exports.Toast = Toast;
    }
})();
