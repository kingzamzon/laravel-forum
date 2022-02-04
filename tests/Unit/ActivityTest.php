<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use App\Thread;
use App\Activity;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_record_activity_when_thread_is_created()
    {
        $this->signIn();

        $thread = create(Thread::class);
        
        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_record_activity_when_reply_is_created()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->assertEquals(2, Activity::count());
    }
}
