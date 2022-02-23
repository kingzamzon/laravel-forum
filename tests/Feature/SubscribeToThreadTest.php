<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use App\ThreadSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_subscribe_to_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions);
        
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_subscribe_from_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');
        
        
        $this->assertCount(0, $thread->subscriptions);
    }


    
}
