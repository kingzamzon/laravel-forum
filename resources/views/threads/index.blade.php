@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @forelse ($threads as $thread)
            <div class="card mb-3">
                <div class="card-header">
                    <div class="level">
                        <h4 class="flex">
                            <a href="{{ $thread->path() }}">
                                @if (Auth::check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong> 
                                @else
                                    {{ $thread->title }}
                                @endif
                            </a>
                        </h4>

                        <a href="{{ $thread->path() }}">
                            {{ $thread->replies_count }}
                            replies
                        </a>
                    </div>
                </div>

                <div class="card-body">
                            
                    <div class="body">{{ $thread->body }}</div>

                </div>
            </div>
            @empty
                <p>Thre are no relevant result at this time.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
