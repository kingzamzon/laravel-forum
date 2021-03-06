<?php

namespace App\Http\Controllers;

use App\User;
use App\Thread;
use App\Channel;
use App\Filters\ThreadFilters;
use App\Http\Requests\ThreadRequest;
use App\Inspections\Spam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) 
        {
            return response()->json($threads);
        }

        return view('threads.index', compact('threads'));
    }

    public function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);
        
        if($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThreadRequest $request, Spam $spam)
    {
        $spam->detect(request('body'));
        
        $thread = Thread::create([
            'user_id' => auth()->user()->id,
            'title' => request('title'),
            'channel_id' => request('channel_id'),
            'body' => request('body')
        ]);

        return redirect()->route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id])
                    ->with('flash', 'Your Thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread)
    {

        // record that user visited this page
        // record a timestamp
        if(auth()->check()) {
            auth()->user()->read($thread);
        }
        // $key = sprintf("users.%s.visits.%s", auth()->id(), $thread->id);

        // cache()->forever($key, Carbon::now());

        $thread = $thread->load('replies.favourites');

        return view('threads.show', [
            'thread' => $thread
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        // thread policy
        $this->authorize('update', $thread);

        // // $thread->replies()->delete();
        // if ($thread->user_id != auth()->id()) {
        //     if (request()->wantsJson()) {
        //         return response(['status' => 'Permssion Denied'], 403);
        //     }

        //     return redirect('/login');
        // }

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');

    }
}
