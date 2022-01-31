<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
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
        $thread = make(Thread::class, ['channel' => 1]);

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    public function test_a_thread_requires_a_title()
    {
        $this->expectException('Illuminate\Validation\ValidationException');

        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_a_thread_requires_a_body()
    {
        $this->expectException('Illuminate\Validation\ValidationException');

        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function test_a_thread_requires_a_valide_channel()
    {
        $this->expectException('Illuminate\Validation\ValidationException');

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

    }

    public function test_a_thread_requires_a_existing_channel()
    {
        $this->expectException('Illuminate\Validation\ValidationException');

        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => 9999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->post('/threads', $thread->toArray());


    }
}
