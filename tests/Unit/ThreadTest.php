<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use App\Channel;
use App\Thread;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    protected $thread;

    public function setUp(): void 
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function test_a_thread_can_make_a_string_path()
    {
        $thread = create(Thread::class);

        // $this->assertEquals('/threads/'.$thread->channel->slug.'/'.$thread->id, $thread->path());
        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->id}",
            $thread->path()
        );
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_a_thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    public function test_a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_belongs_to_a_channel()
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $thread = create(Thread::class);

        // when the user subscribes to the thread
        $thread->subscribe($userId = 1);

        // then we should be able to fetch all threads that the user has subscribed to
        $this->assertEquals(
            1, 
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    public function test_a_thread_can_be_unsubscribed_from()
    {
        $thread = create(Thread::class);

        // when the user subscribes to the thread
        $thread->subscribe($userId = 1);
        
        $thread->unsubscribe($userId);

        // then we should be able to fetch all threads that the user has subscribed to
        $this->assertCount(
            0, 
            $thread->subscriptions
        );
    }


    public function test_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create(Thread::class);

        // when the user subscribes to the thread
        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();
        
        $this->assertTrue($thread->isSubscribedTo);
    }


    public function test_a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        // when the user subscribes to the thread
        $this->signIn();

        $thread = create(Thread::class);

        tap(auth()->user(), function ($user) use ($thread) {

            $this->assertTrue($thread->hasUpdatesFor($user));
    
            $user->read($thread);
            // simulate that the user visited the thread
            // cache()->forever(
            //     $user->visitedThreadCacheKey($thread), 
            //     \Carbon\Carbon::now());
    
            $this->assertFalse($thread->hasUpdatesFor($user));
        });

    }
}
