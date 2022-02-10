@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="{{ route('user.profile', ['user' => $thread->creator->name]) }}">
                            {{ $thread->creator->name }}
                            </a> posted: 
                            {{ $thread->title }}
                        </span>

                        @can('update', $thread)
                        <form action="{{ $thread->path() }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete Thread
                            </button>
                        </form>
                        @endcan
                    </div>

                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>

            <replies :data="{{ $thread->replies }}" @removed="repliesCount--" 
                ></replies>
            

            @if (auth()->check())
                <form action="{{ route('threads.replies', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" cols="70" rows="5" placeholder="Have something to say?"></textarea>
                    </div>

                    <button type="submit" class="btn btn-default">Post</button>
                </form>
            @else 
                <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion</p>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                

                <div class="card-body">
                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans() }} by
                        <a href="{{ route('user.profile', ['user' => $thread->creator->name]) }}">{{ $thread->creator->name }}</a> and currently has 
                        <span v-text="repliesCount"></span>
                         comments
                        {{-- {{ str_plural('comment', $thread->replies_count) }} --}}
                    </p>
                </div>
            </div>
        </div>
        
    </div>
</div>
</thread-view>
@endsection
