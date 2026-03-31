<?php

namespace Haxneeraj\LivewireToaster\Tests\Unit;

use Haxneeraj\LivewireToaster\Tests\TestCase;
use Haxneeraj\LivewireToaster\ToastManager;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_merges_config(): void
    {
        $this->assertNotNull(config('toaster'));
        $this->assertEquals('top-right', config('toaster.position'));
        $this->assertEquals(3000, config('toaster.duration'));
    }

    /** @test */
    public function it_has_all_config_keys(): void
    {
        $config = config('toaster');

        $this->assertArrayHasKey('position', $config);
        $this->assertArrayHasKey('duration', $config);
        $this->assertArrayHasKey('closable', $config);
        $this->assertArrayHasKey('close_on_click', $config);
        $this->assertArrayHasKey('pause_on_hover', $config);
        $this->assertArrayHasKey('max_toasts', $config);
        $this->assertArrayHasKey('queue', $config);
        $this->assertArrayHasKey('suppress_duplicates', $config);
        $this->assertArrayHasKey('replace_duplicates', $config);
        $this->assertArrayHasKey('show_icon', $config);
        $this->assertArrayHasKey('show_progress_bar', $config);
        $this->assertArrayHasKey('event_name', $config);
        $this->assertArrayHasKey('styles', $config);
    }

    /** @test */
    public function it_has_style_configs_for_all_types(): void
    {
        $styles = config('toaster.styles');

        $this->assertArrayHasKey('success', $styles);
        $this->assertArrayHasKey('error', $styles);
        $this->assertArrayHasKey('warning', $styles);
        $this->assertArrayHasKey('info', $styles);

        foreach ($styles as $type => $style) {
            $this->assertArrayHasKey('bg', $style, "Missing 'bg' for {$type}");
            $this->assertArrayHasKey('border', $style, "Missing 'border' for {$type}");
            $this->assertArrayHasKey('text', $style, "Missing 'text' for {$type}");
            $this->assertArrayHasKey('icon_color', $style, "Missing 'icon_color' for {$type}");
            $this->assertArrayHasKey('progress_bg', $style, "Missing 'progress_bg' for {$type}");
        }
    }

    /** @test */
    public function it_registers_toast_manager_as_singleton(): void
    {
        $manager1 = app(ToastManager::class);
        $manager2 = app(ToastManager::class);

        $this->assertSame($manager1, $manager2);
    }

    /** @test */
    public function it_registers_toast_alias(): void
    {
        $manager = app('toast');

        $this->assertInstanceOf(ToastManager::class, $manager);
    }

    /** @test */
    public function it_loads_views(): void
    {
        $viewFactory = $this->app['view'];
        $this->assertTrue($viewFactory->exists('livewire-toaster::components.toast-hub'));
    }
}
