<?php

namespace Haxneeraj\LivewireToaster\Tests\Unit;

use Haxneeraj\LivewireToaster\Http\Middleware\InjectToasts;
use Haxneeraj\LivewireToaster\Tests\TestCase;
use Haxneeraj\LivewireToaster\ToastManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InjectToastsMiddlewareTest extends TestCase
{
    /** @test */
    public function it_injects_script_when_toasts_are_pending(): void
    {
        /** @var ToastManager $manager */
        $manager = app(ToastManager::class);
        $manager->success('Hello!');

        $middleware = new InjectToasts($manager);
        $request = Request::create('/test', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('<html><body></body></html>', 200, [
                'Content-Type' => 'text/html',
            ]);
        });

        $content = $response->getContent();
        $this->assertStringContainsString('<script>', $content);
        $this->assertStringContainsString('Hello!', $content);
        $this->assertStringContainsString('dispatchEvent', $content);
    }

    /** @test */
    public function it_does_not_inject_when_no_toasts(): void
    {
        /** @var ToastManager $manager */
        $manager = app(ToastManager::class);

        $middleware = new InjectToasts($manager);
        $request = Request::create('/test', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('<html><body></body></html>', 200, [
                'Content-Type' => 'text/html',
            ]);
        });

        $content = $response->getContent();
        $this->assertStringNotContainsString('<script>', $content);
    }

    /** @test */
    public function it_does_not_inject_into_json_responses(): void
    {
        /** @var ToastManager $manager */
        $manager = app(ToastManager::class);
        $manager->success('Ignored');

        $middleware = new InjectToasts($manager);
        $request = Request::create('/api/test', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('{"status":"ok"}', 200, [
                'Content-Type' => 'application/json',
            ]);
        });

        $content = $response->getContent();
        $this->assertStringNotContainsString('<script>', $content);
    }

    /** @test */
    public function it_picks_up_session_flashed_toasts(): void
    {
        /** @var ToastManager $manager */
        $manager = app(ToastManager::class);
        $manager->error('Session toast');
        $manager->flashToSession();

        $middleware = new InjectToasts($manager);
        $request = Request::create('/test', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('<html><body></body></html>', 200, [
                'Content-Type' => 'text/html',
            ]);
        });

        $content = $response->getContent();
        $this->assertStringContainsString('Session toast', $content);
    }
}
