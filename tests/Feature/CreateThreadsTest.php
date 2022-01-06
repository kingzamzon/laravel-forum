<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{

    /** @test */
    public function test_guest_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        
        $thread = factory(Thread::class)->make();

        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
        $this->actingAs($user = factory(User::class)->create());

        // raw returns the array, make returns the instance
        $thread = factory(Thread::class)->make();

        $this->post('/threads', $thread->toArray());

        $this->get('/threads')
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
