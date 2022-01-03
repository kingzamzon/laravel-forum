<?php

namespace Tests\Feature;

use App\Thread;
use App\Reply;
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
        $response = $this->get('/threads/'.$this->thread->id);
        $response->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory(Reply::class)
                    ->create(['thread_id' => $this->thread->id]);

        $this->get('/threads/'.$this->thread->id)
                        ->assertSee($reply->body);
    }
    
}
