@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New Thread</div>

                <div class="card-body">
                    @if (count($errors))
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                   @endif

                   <form action="{{ route('threads.store') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="channel">Choose a Channel:</label>
                            <select name="channel_id" class="form-control" id="channel" required>
                                <option>Choose One</option>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->id}}" {{ old('channel') == $channel->id ? 'selected' : ''}}>{{ $channel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input name="title" type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="body">Body:</label>
                            <textarea name="body" class="form-control" id="body" cols="60" rows="10" required>{{ old('body') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Publish</button>
                   </form>

                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
