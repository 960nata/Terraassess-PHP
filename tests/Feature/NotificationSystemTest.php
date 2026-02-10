<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_notifications()
    {
        Notification::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
        $response->assertViewHas('notifications');
    }

    /** @test */
    public function user_can_mark_notification_as_read()
    {
        $notification = Notification::factory()->create([
            'user_id' => $this->user->id,
            'is_read' => false
        ]);

        $response = $this->post(route('notifications.mark-read', $notification->id));

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true
        ]);
    }

    /** @test */
    public function user_can_mark_all_notifications_as_read()
    {
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'is_read' => false
        ]);

        $response = $this->post(route('notifications.mark-all-read'));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->user->id,
            'is_read' => false
        ]);
    }

    /** @test */
    public function user_can_delete_notification()
    {
        $notification = Notification::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('notifications.destroy', $notification->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id
        ]);
    }

    /** @test */
    public function user_can_clear_all_notifications()
    {
        Notification::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('notifications.clear-all'));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function api_returns_unread_count()
    {
        Notification::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'is_read' => false
        ]);

        Notification::factory()->create([
            'user_id' => $this->user->id,
            'is_read' => true
        ]);

        $response = $this->get(route('api.notifications.unread-count'));

        $response->assertStatus(200);
        $response->assertJson(['unreadCount' => 2]);
    }

    /** @test */
    public function api_returns_latest_notifications()
    {
        $notifications = Notification::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->get(route('api.notifications.latest'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'notifications' => [
                '*' => [
                    'id',
                    'title',
                    'message',
                    'type',
                    'is_read',
                    'created_at'
                ]
            ],
            'unreadCount'
        ]);
    }

    /** @test */
    public function user_cannot_access_other_users_notifications()
    {
        $otherUser = User::factory()->create();
        $notification = Notification::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->post(route('notifications.mark-read', $notification->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function notification_bell_shows_correct_count()
    {
        Notification::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'is_read' => false
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('5'); // Should show unread count
    }
}
