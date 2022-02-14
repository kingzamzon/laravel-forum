<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use App\Reply;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadTest extends TestCase
{
    // use DatabaseMigrations;
    public function setUp(): void 
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }
    
    /**
     * @test
     */
    public function test_a_user_can_browse_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_a_single_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory(Reply::class)
                    ->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
                        ->assertSee($reply->body);
    }

    // public function test_a_user_can_filter_threads_according_to_a_channel()
    // {
    //     $channel = create(Channel::class);
    //     $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
    //     $threadNotInChannel = create(Thread::class);

    //     $this->get('/threads/'.$channel->slug)
    //         ->assertSee($threadInChannel->title)
    //         ->assertDontSee($threadNotInChannel->title);
    // }

    public function test_a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create(User::class, ['name' => 'JohnDoe']));

        $threadByJohn = create(Thread::class, ['user_id' => auth()->id()]);
        $threadNotByJohn = create(Thread::class);

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    public function test_a_user_can_filter_thread_by_popularity()
    {
        $threadWithTwoReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }
    
    public function test_a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();
        
        $this->assertCount(1, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
