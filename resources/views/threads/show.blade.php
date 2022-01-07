@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="#">
                    {{ $thread->creator->name }}
                    </a> posted: 
                    {{ $thread->title }}
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
        </div>
    </div>

    <br> <br>

    <div class="row justify-content-center">
        @foreach ($thread->replies as $reply)
            @include('threads.reply')
        @endforeach
    </div>

    <br> <br>

    @if (auth()->check())
        
    <div class="row justify-content-center">
        <form action="{{ route('threads.replies', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea name="body" id="body" class="form-control" cols="70" rows="5" placeholder="Have something to say?"></textarea>
            </div>

            <button type="submit" class="btn btn-default">Post</button>
        </form>
    </div>

    @else 
    <div class="row justify-content-center">
        <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion</p>
    </div>
    @endif

</div>
@endsection
