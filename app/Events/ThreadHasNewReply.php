<?php

namespace App\Events;

use App\Thread;
use Illuminate\Queue\SerializesModels;

class ThreadHasNewReply
{
    use SerializesModels;

    public $thread;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @param App\Thread $thread
     * @param App\Reply $reply
     * 
     * @return void
     */
    public function __construct($thread, $reply)
    {
        $this->$thread = $thread;
        $this->reply = $reply;
    }


}
