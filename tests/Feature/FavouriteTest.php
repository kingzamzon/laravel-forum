<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Favourite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavouriteTest extends TestCase
{

    /** @test */
    public function test_guests_cannot_favourite_anything()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('replies/1/favourites');
    }

    /** @test */
    public function test_an_authenticated_user_can_favourite_any_reply()
    {
        $this->signIn();
        
        $reply = create(Reply::class);

        $this->post('replies/'. $reply->id. '/favourites');

        $this->assertCount(1, $reply->favourites);
    }

    /** @test */
    public function test_an_authenticated_user_can_unfavourite_any_reply()
    {
        $this->signIn();
        
        $reply = create(Reply::class);

        $reply->favourite();

        
        $this->delete('replies/'. $reply->id. '/favourites');
        $this->assertCount(0, $reply->favourites);

    }

    /** @test */
    public function test_an_authenticated_user_may_only_favourite_a_reply_once()
    {
        $this->signIn();
        
        $reply = create(Reply::class);

        try {
            $this->post('replies/'. $reply->id. '/favourites');
            $this->post('replies/'. $reply->id. '/favourites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to record record twice');
        }

        $this->assertCount(1, $reply->favourites);
    }
}