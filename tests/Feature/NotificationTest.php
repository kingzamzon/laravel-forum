<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use App\ThreadSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();

    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_notification_is_prepared_when_a_subscribed_thread_recieves_a_new_reply_that_is_not_by_current_user()
    {

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

        create(DatabaseNotification::class);

        $this->assertCount(
            1, 
            $this->getJson("/profile/" . auth()->user()->name . "/notifications")->json()
        );

    }

    public function test_a_user_can_mark_a_notification()
    {
        create(DatabaseNotification::class);

        tap(auth()->user(), function ($user) {

            $this->assertCount(1, $user->fresh()->unreadNotifications);
        
            $this->delete("/profile/{$user->name}/notifications/". $user->unreadNotifications->first()->id);
    
            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
