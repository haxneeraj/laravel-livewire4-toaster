<?php

namespace Haxneeraj\LivewireToaster\Tests\Unit;

use Haxneeraj\LivewireToaster\Tests\TestCase;
use Haxneeraj\LivewireToaster\ToastManager;

class ToastManagerTest extends TestCase
{
    protected ToastManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = app(ToastManager::class);
    }

    /** @test */
    public function it_can_create_a_success_toast(): void
    {
        $this->manager->success('Operation completed');

        $toasts = $this->manager->toArray();

        $this->assertCount(1, $toasts);
        $this->assertEquals('success', $toasts[0]['type']);
        $this->assertEquals('Operation completed', $toasts[0]['message']);
        $this->assertNull($toasts[0]['title']);
    }

    /** @test */
    public function it_can_create_an_error_toast(): void
    {
        $this->manager->error('Something went wrong', 'Error');

        $toasts = $this->manager->toArray();

        $this->assertCount(1, $toasts);
        $this->assertEquals('error', $toasts[0]['type']);
        $this->assertEquals('Something went wrong', $toasts[0]['message']);
        $this->assertEquals('Error', $toasts[0]['title']);
    }

    /** @test */
    public function it_can_create_an_info_toast(): void
    {
        $this->manager->info('FYI');

        $toasts = $this->manager->toArray();

        $this->assertCount(1, $toasts);
        $this->assertEquals('info', $toasts[0]['type']);
    }

    /** @test */
    public function it_can_create_a_warning_toast(): void
    {
        $this->manager->warning('Be careful!');

        $toasts = $this->manager->toArray();

        $this->assertCount(1, $toasts);
        $this->assertEquals('warning', $toasts[0]['type']);
    }

    /** @test */
    public function it_can_create_a_generic_toast(): void
    {
        $this->manager->toast('custom', 'Custom toast', 'Title');

        $toasts = $this->manager->toArray();

        $this->assertCount(1, $toasts);
        $this->assertEquals('custom', $toasts[0]['type']);
        $this->assertEquals('Custom toast', $toasts[0]['message']);
        $this->assertEquals('Title', $toasts[0]['title']);
    }

    /** @test */
    public function it_generates_unique_ids(): void
    {
        $this->manager->success('First');
        $this->manager->success('Second');

        $toasts = $this->manager->toArray();

        $this->assertCount(2, $toasts);
        $this->assertNotEquals($toasts[0]['id'], $toasts[1]['id']);
    }

    /** @test */
    public function it_uses_config_defaults_for_duration_and_position(): void
    {
        $this->manager->success('Hello');

        $toasts = $this->manager->toArray();

        $this->assertEquals(3000, $toasts[0]['duration']);
        $this->assertEquals('top-right', $toasts[0]['position']);
    }

    /** @test */
    public function it_allows_option_overrides(): void
    {
        $this->manager->success('Quick!', null, [
            'duration' => 1000,
            'position' => 'bottom-left',
        ]);

        $toasts = $this->manager->toArray();

        $this->assertEquals(1000, $toasts[0]['duration']);
        $this->assertEquals('bottom-left', $toasts[0]['position']);
    }

    /** @test */
    public function it_can_flush_toasts(): void
    {
        $this->manager->success('One');
        $this->manager->error('Two');

        $flushed = $this->manager->flush();

        $this->assertCount(2, $flushed);
        $this->assertCount(0, $this->manager->toArray());
    }

    /** @test */
    public function it_can_check_if_has_pending(): void
    {
        $this->assertFalse($this->manager->hasPending());

        $this->manager->info('Test');
        $this->assertTrue($this->manager->hasPending());
    }

    /** @test */
    public function it_can_count_pending_toasts(): void
    {
        $this->assertEquals(0, $this->manager->count());

        $this->manager->success('One');
        $this->manager->error('Two');
        $this->manager->info('Three');

        $this->assertEquals(3, $this->manager->count());
    }

    /** @test */
    public function it_supports_method_chaining(): void
    {
        $result = $this->manager
            ->success('First')
            ->error('Second')
            ->warning('Third');

        $this->assertInstanceOf(ToastManager::class, $result);
        $this->assertCount(3, $this->manager->toArray());
    }

    /** @test */
    public function it_can_flash_to_session(): void
    {
        $this->manager->success('Flash me!');
        $this->manager->flashToSession();

        $this->assertFalse($this->manager->hasPending());

        $sessionToasts = session('livewire_toaster');
        $this->assertCount(1, $sessionToasts);
        $this->assertEquals('Flash me!', $sessionToasts[0]['message']);
    }

    /** @test */
    public function it_returns_correct_event_name(): void
    {
        $this->assertEquals('toast', $this->manager->eventName());
    }
}
