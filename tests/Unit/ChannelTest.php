<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_channel_consists_of_thread()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
