<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
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
}
