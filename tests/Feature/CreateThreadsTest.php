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

        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    // public function test_guests_cannot_see_the_create_thread_page()
    // {
    //     $this->get('/threads/create')
    //         ->assertRedirect('/login');
    // }

    /** @test */
    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        // raw returns the array, make returns the instance
        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());

        $this->get('/threads')
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
