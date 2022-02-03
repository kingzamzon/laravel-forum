<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use App\Favourite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{

    /** @test */
    public function test_a_user_has_a_profile()
    {
        $user = create(User::class);

        $this->get('/profile/'. $user->name)
            ->assertSee($user->name);
    }

    /** @test */
    public function test_profiles_display_all_thread_created_by_the_associated_user()
    {
        $user = create(User::class);

        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->get('/profile/'. $user->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

}