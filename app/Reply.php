<?php

namespace App;

use App\Favourable;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favourable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];
    
    protected $appends = ['favouritesCount', 'isFavourited'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });

    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path(). "#reply-{$this->id}";
    }


}
