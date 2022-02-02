<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Favourite;
use Illuminate\Http\Request;
use DB;

class FavouriteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        // due to polymorphic relationship
        // $reply->favourites()->create(['user_id' => auth()->id()]);

        return $reply->favourite();
    }

}
