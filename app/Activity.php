<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function subject()
    {
        // this will figure out the relationship
        return $this->morphTo();
    }

    public function feed($user)
    {
        
    }
}
