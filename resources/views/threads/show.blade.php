@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('user.profile', ['user' => $thread->creator->name]) }}">
                    {{ $thread->creator->name }}
                    </a> posted: 
                    {{ $thread->title }}
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
            
            @foreach ($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{ $replies->links() }}

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
                        {{ $thread->replies_count }} comments
                        {{-- {{ str_plural('comment', $thread->replies_count) }} --}}
                    </p>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
