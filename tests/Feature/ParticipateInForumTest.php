<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    /** @test */
    public function test_unauthenticated_user_may_not_add_replies()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        
        $this->post('/threads/some-channel/1/replies', []);    
    }

    /** @test */
    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        // $user = factory(App\User::class)->create();
        // create user and authenticate it.
        $this->be(create(User::class));
        
        $thread = create(Thread::class);


        // make reply so it won't create twice
        $reply = make(Reply::class);
        $this->post($thread->path() .'/replies', $reply->toArray());
    
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
