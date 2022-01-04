<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use App\Thread;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_thread_has_replies()
    {
        $thread = factory(Thread::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }

    public function test_a_thread_has_a_creator()
    {
        $thread = factory(Thread::class)->create();

        $this->assertInstanceOf(User::class, $thread->creator);
    }
}
