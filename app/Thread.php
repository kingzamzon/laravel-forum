<?php

namespace App;

// use App\RecordsActivity;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function ($builder) {
        //     $builder->withCount('replies');
        // });

        // ::withoutGlobalScopes();
        // static::addGlobalScope('creator', function ($builder) {
        //     $builder->with('creator');
        // });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
            // $thread->replies->each(function ($reply) {
            //     $reply->delete();
            // });
        });

        
    }


    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
        // return '/threads/' . $this->channel->slug . '/' . $this->id;
    }

    /**
     * Getters
     */
    // public function getReplyCountAttribute()
    // {
    //     return $this->replies()->count();
    // }

    public function replies()
    {
        return $this->hasMany(Reply::class)
                    ->orderBy('id', 'desc');
                // ->withCount('favourites')
                // ->with('owner');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply =  $this->replies()->create($reply);

        // event(new ThreadHasNewReply($this, $reply));
        
       $this->notifySubscribers($reply);

        return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
    
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
                ->where('user_id', auth()->id())
                ->exists();
    }

    public function hasUpdatesFor($user = null)
    {
        // look in the cache for the proper key
        // compare that carbon instance with the $thread->updated_at

        // $key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);
        
        $user = $user ?: auth()->user();
        
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }
}
