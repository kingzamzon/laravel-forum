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

    public function test_a_reply_requires_a_body()
    {
        $this->expectException('Illuminate\Validation\ValidationException');

        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class, ["body" => null]);

        $this->post($thread->path() .'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    public function test_unauthorized_user_cannot_delete_replies()
    {
        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $reply = create(Reply::class);

        // for signin user
        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_authorized_user_cannot_delete_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        // for signin user
        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302);

        // 
        $this->assertDatabaseMissing('replies', [
                    'id' => $reply->id, 
                    'user_id' => auth()->id()
                ]);
    }

    public function test_unauthorized_user_update_replies()
    {
        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $reply = create(Reply::class);

        // for signin user
        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_authorized_user_update_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $updatedReply = "'You have been changed, fool";
        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', [
                    'id' => $reply->id, 
                    'body' => $updatedReply
                ]);
    }

}
