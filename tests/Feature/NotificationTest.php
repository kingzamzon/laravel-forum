<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use App\ThreadSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_notification_is_prepared_when_a_subscribed_thread_recieves_a_new_reply_that_is_not_by_current_user()
    {
        $this->signIn();

        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->notifications);
       
        // Then each time a new reply is left...
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

        // a notification should be prepare for the user
        $this->assertCount(0, auth()->user()->fresh()->notifications);

        // Then each time a new reply is left...
        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here'
        ]);

        // a notification should be prepare for the user
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    } 

    public function test_a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();

        $thread = create(Thread::class)->subscribe();

        // Then each time a new reply is left...
        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here'
        ]);

        $user = auth()->user();

        $response = $this->getJson("/profile/{$user->name}/notifications")->json();

        $this->assertCount(1, $response);

    }

    public function test_a_user_can_mark_a_notification()
    {
        $this->signIn();

        $thread = create(Thread::class)->subscribe();

        // Then each time a new reply is left...
        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here'
        ]);

        $user = auth()->user();

        // a notification should be prepare for the user
        $this->assertCount(1, $user->fresh()->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("/profile/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
