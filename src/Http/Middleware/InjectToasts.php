<?php

namespace Haxneeraj\LivewireToaster\Http\Middleware;

use Closure;
use Haxneeraj\LivewireToaster\ToastManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class InjectToasts
{
    /**
     * The toast manager instance.
     */
    protected ToastManager $toastManager;

    /**
     * Create a new middleware instance.
     */
    public function __construct(ToastManager $toastManager)
    {
        $this->toastManager = $toastManager;
    }

    /**
     * Handle an incoming request.
     *
     * Injects pending toasts (from facade/helper or session flash) as
     * inline <script> tags before </body>, dispatching browser events
     * that the Alpine toast-hub component will catch.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only inject into HTML responses
        if (! $this->shouldInject($response)) {
            return $response;
        }

        // Collect toasts from manager + session
        $toasts = $this->collectToasts();

        if (empty($toasts)) {
            return $response;
        }

        $script = $this->buildScript($toasts);
        $content = $response->getContent();

        // Insert script before closing </body>
        $content = str_replace('</body>', $script . '</body>', $content);
        $response->setContent($content);

        return $response;
    }

    /**
     * Determine if we should inject into this response.
     */
    protected function shouldInject(Response $response): bool
    {
        if (! $response instanceof HttpResponse) {
            return false;
        }

        $contentType = $response->headers->get('Content-Type', '');

        return str_contains($contentType, 'text/html')
            || empty($contentType);
    }

    /**
     * Collect toasts from manager and session.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function collectToasts(): array
    {
        $toasts = $this->toastManager->flush();

        // Also grab any toasts flashed to the session (redirect scenarios)
        $sessionToasts = session()->pull('livewire_toaster', []);

        return array_merge($toasts, $sessionToasts);
    }

    /**
     * Build the inline script that dispatches toast events.
     */
    protected function buildScript(array $toasts): string
    {
        $eventName = config('toaster.event_name', 'toast');
        $json = json_encode($toasts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

        return <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toasts = {$json};
    toasts.forEach(function(toast) {
        window.dispatchEvent(new CustomEvent('{$eventName}', { detail: toast }));
    });
});
</script>
HTML;
    }
}
