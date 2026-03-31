{{-- Toast Hub — Livewire 4 + Alpine.js Toast Container --}}
{{-- Place this component in your layout: <x-livewire-toaster::toast-hub /> --}}

@php
    $config = config('toaster', []);
    $position = $config['position'] ?? 'top-right';
    $duration = $config['duration'] ?? 3000;
    $closable = $config['closable'] ?? true;
    $closeOnClick = $config['close_on_click'] ?? true;
    $pauseOnHover = $config['pause_on_hover'] ?? true;
    $maxToasts = $config['max_toasts'] ?? 5;
    $queue = $config['queue'] ?? false;
    $suppressDuplicates = $config['suppress_duplicates'] ?? false;
    $replaceDuplicates = $config['replace_duplicates'] ?? false;
    $showIcon = $config['show_icon'] ?? true;
    $showProgressBar = $config['show_progress_bar'] ?? true;
    $eventName = $config['event_name'] ?? 'toast';
    $styles = $config['styles'] ?? [];
@endphp

<div
    x-data="toastHub({
        position: '{{ $position }}',
        duration: {{ $duration }},
        closable: {{ $closable ? 'true' : 'false' }},
        closeOnClick: {{ $closeOnClick ? 'true' : 'false' }},
        pauseOnHover: {{ $pauseOnHover ? 'true' : 'false' }},
        maxToasts: {{ $maxToasts }},
        queue: {{ $queue ? 'true' : 'false' }},
        suppressDuplicates: {{ $suppressDuplicates ? 'true' : 'false' }},
        replaceDuplicates: {{ $replaceDuplicates ? 'true' : 'false' }},
        showIcon: {{ $showIcon ? 'true' : 'false' }},
        showProgressBar: {{ $showProgressBar ? 'true' : 'false' }},
        styles: {{ Js::from($styles) }}
    })"
    x-on:{{ $eventName }}.window="handleEvent($event)"
    class="fixed z-[99999] pointer-events-none"
    :class="containerClasses"
    role="status"
    aria-live="polite"
    aria-atomic="true"
>
    {{-- Toast Cards --}}
    <template x-for="toast in visibleToasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            :class="getToastClasses(toast)"
            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-xl border shadow-lg backdrop-blur-sm mb-3 cursor-default"
            x-on:click="closeOnClick && dismiss(toast.id)"
            x-on:mouseenter="pauseOnHover && pause(toast.id)"
            x-on:mouseleave="pauseOnHover && resume(toast.id)"
        >
            <div class="p-4">
                <div class="flex items-start gap-3">
                    {{-- Icon --}}
                    <template x-if="showIcon">
                        <div class="flex-shrink-0 mt-0.5" :class="getIconClasses(toast)">
                            {{-- Success Icon --}}
                            <template x-if="toast.type === 'success'">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            </template>

                            {{-- Error Icon --}}
                            <template x-if="toast.type === 'error'">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </template>

                            {{-- Warning Icon --}}
                            <template x-if="toast.type === 'warning'">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </template>

                            {{-- Info Icon --}}
                            <template x-if="toast.type === 'info'">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                </svg>
                            </template>
                        </div>
                    </template>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <template x-if="toast.title">
                            <p class="text-sm font-semibold leading-5" x-text="toast.title" :class="getTextClasses(toast)"></p>
                        </template>
                        <p class="text-sm leading-5" :class="[toast.title ? 'mt-1 opacity-90' : '', getTextClasses(toast)]" x-text="toast.message"></p>
                    </div>

                    {{-- Close Button --}}
                    <template x-if="closable">
                        <button
                            type="button"
                            class="flex-shrink-0 rounded-lg p-1 opacity-60 transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-offset-1"
                            :class="getTextClasses(toast)"
                            x-on:click.stop="dismiss(toast.id)"
                            aria-label="Close notification"
                        >
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Progress Bar --}}
            <template x-if="showProgressBar && toast.duration > 0">
                <div class="h-1 w-full overflow-hidden bg-black/5 dark:bg-white/5">
                    <div
                        class="h-full transition-all ease-linear"
                        :class="getProgressClasses(toast)"
                        :style="`width: ${toast.progress}%; transition-duration: ${toast.paused ? '0ms' : '100ms'}`"
                    ></div>
                </div>
            </template>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('toastHub', (config) => ({
        // Config
        position: config.position || 'top-right',
        duration: config.duration || 3000,
        closable: config.closable !== false,
        closeOnClick: config.closeOnClick !== false,
        pauseOnHover: config.pauseOnHover !== false,
        maxToasts: config.maxToasts || 5,
        queue: config.queue || false,
        suppressDuplicates: config.suppressDuplicates || false,
        replaceDuplicates: config.replaceDuplicates || false,
        showIcon: config.showIcon !== false,
        showProgressBar: config.showProgressBar !== false,
        styles: config.styles || {},

        // State
        toasts: [],
        pending: [],
        timers: {},

        get visibleToasts() {
            return this.toasts.filter(t => t.visible);
        },

        get containerClasses() {
            const base = 'flex flex-col p-4 sm:p-6 gap-0';
            const positions = {
                'top-right':     'top-0 right-0 items-end',
                'top-left':      'top-0 left-0 items-start',
                'top-center':    'top-0 left-1/2 -translate-x-1/2 items-center',
                'bottom-right':  'bottom-0 right-0 items-end',
                'bottom-left':   'bottom-0 left-0 items-start',
                'bottom-center': 'bottom-0 left-1/2 -translate-x-1/2 items-center',
            };
            return `${base} ${positions[this.position] || positions['top-right']}`;
        },

        handleEvent(event) {
            const detail = event.detail || {};

            // Source 1: Livewire 4 trait dispatches with `toast: $payload`
            //   → event.detail = { toast: { type, message, ... } }
            // Source 2: InjectToasts middleware & toast.js dispatch CustomEvent
            //   → event.detail = { type, message, ... }
            // Source 3: Alpine $dispatch('toast', { type, message, ... })
            //   → event.detail = { type, message, ... }

            let payload;
            if (detail.toast && typeof detail.toast === 'object') {
                payload = detail.toast;
            } else if (detail.type && detail.message) {
                payload = detail;
            } else if (Array.isArray(detail) && detail[0]) {
                payload = detail[0];
            } else {
                payload = detail;
            }

            if (payload && payload.message) {
                this.addToast(payload);
            }
        },

        addToast(data) {
            const toast = {
                id: data.id || this.generateId(),
                type: data.type || 'info',
                message: data.message || '',
                title: data.title || null,
                duration: data.duration !== undefined ? data.duration : this.duration,
                position: data.position || this.position,
                visible: true,
                paused: false,
                progress: 100,
                createdAt: Date.now(),
            };

            // Suppress duplicates check
            if (this.suppressDuplicates) {
                const isDuplicate = this.toasts.some(
                    t => t.type === toast.type && t.message === toast.message && t.visible
                );
                if (isDuplicate) return;
            }

            // Replace duplicates check
            if (this.replaceDuplicates) {
                const existingIndex = this.toasts.findIndex(
                    t => t.type === toast.type && t.message === toast.message && t.visible
                );
                if (existingIndex !== -1) {
                    this.dismiss(this.toasts[existingIndex].id);
                }
            }

            // Queue mode
            if (this.queue && this.visibleToasts.length >= 1) {
                this.pending.push(toast);
                return;
            }

            // Max toasts limit
            if (this.maxToasts > 0 && this.visibleToasts.length >= this.maxToasts) {
                // Remove the oldest visible toast
                const oldest = this.visibleToasts[0];
                if (oldest) this.dismiss(oldest.id);
            }

            this.toasts.push(toast);

            // Start auto-dismiss
            if (toast.duration > 0) {
                this.startTimer(toast);
            }
        },

        startTimer(toast) {
            const startTime = Date.now();
            const tick = () => {
                if (!toast.visible) return;

                if (toast.paused) {
                    requestAnimationFrame(tick);
                    return;
                }

                const elapsed = Date.now() - startTime - (toast._pausedDuration || 0);
                const remaining = toast.duration - elapsed;
                toast.progress = Math.max(0, (remaining / toast.duration) * 100);

                if (remaining <= 0) {
                    this.dismiss(toast.id);
                } else {
                    this.timers[toast.id] = requestAnimationFrame(tick);
                }
            };

            toast._pausedDuration = 0;
            this.timers[toast.id] = requestAnimationFrame(tick);
        },

        dismiss(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) {
                toast.visible = false;

                // Cancel timer
                if (this.timers[id]) {
                    cancelAnimationFrame(this.timers[id]);
                    delete this.timers[id];
                }

                // Clean up after transition
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);

                    // Show next queued toast
                    if (this.pending.length > 0) {
                        const next = this.pending.shift();
                        this.addToast(next);
                    }
                }, 300);
            }
        },

        pause(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) {
                toast.paused = true;
                toast._pauseStart = Date.now();
            }
        },

        resume(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast && toast.paused) {
                toast.paused = false;
                if (toast._pauseStart) {
                    toast._pausedDuration = (toast._pausedDuration || 0) + (Date.now() - toast._pauseStart);
                    toast._pauseStart = null;
                }
            }
        },

        getToastClasses(toast) {
            const style = this.styles[toast.type] || {};
            return `${style.bg || ''} ${style.border || ''}`;
        },

        getTextClasses(toast) {
            const style = this.styles[toast.type] || {};
            return style.text || '';
        },

        getIconClasses(toast) {
            const style = this.styles[toast.type] || {};
            return style.icon_color || '';
        },

        getProgressClasses(toast) {
            const style = this.styles[toast.type] || {};
            return style.progress_bg || 'bg-gray-500';
        },

        generateId() {
            return `toast-${Date.now()}-${Math.random().toString(36).substring(2, 9)}`;
        }
    }));
});
</script>
