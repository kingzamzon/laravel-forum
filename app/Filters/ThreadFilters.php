<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];
    /**
     * Filter the query by given username
     * @param $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->first();
        
        return $this->builder->where('user_id', $user->id);
    }
}