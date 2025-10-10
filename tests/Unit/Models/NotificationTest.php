<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $notification = Notification::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $notification->user);
        $this->assertEquals($user->id, $notification->user->id);
    }

    /** @test */
    public function it_can_be_created_with_createForUser_method()
    {
        $user = User::factory()->create();
        
        $notification = Notification::createForUser(
            $user->id,
            'Test Title',
            'Test Message',
            'success',
            'tugas',
            123
        );

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Test Title',
            'message' => 'Test Message',
            'type' => 'success',
            'related_type' => 'tugas',
            'related_id' => 123,
            'is_read' => false
        ]);
    }

    /** @test */
    public function it_casts_is_read_to_boolean()
    {
        $notification = Notification::factory()->create(['is_read' => 1]);
        
        $this->assertTrue($notification->is_read);
        $this->assertIsBool($notification->is_read);
    }

    /** @test */
    public function it_has_default_values()
    {
        $user = User::factory()->create();
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Test',
            'message' => 'Test message'
        ]);

        $this->assertEquals('info', $notification->type);
        $this->assertFalse($notification->is_read);
        $this->assertNull($notification->related_type);
        $this->assertNull($notification->related_id);
    }
}
