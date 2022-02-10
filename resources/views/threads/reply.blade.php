<reply :attributes="{{ $reply }}" inline-template v-cloak>
<div id="reply-{{ $reply->id }}" class="mt-3 mb-3">
    <div class="card">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('user.profile', ['user' => $reply->owner->name]) }}" >
                        {{ $reply->owner->name }} 
                    </a>
                    said
                    {{ $reply->created_at->diffForHumans() }}
                </h5>

                @if(Auth::check())
                <div>
                    <favourite :reply="{{ $reply }}"></favourite>
                </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body">
            </div>
        </div>

        @can('update', $reply)
        <div class="card-footer level">
            <button class="btn btn-warning btn-xs mr-1" @click="editing = true">Edit</button>
            
            <form action="{{ route('replies.delete', ['reply' => $reply->id]) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
            </form>
        </div>
        @endcan
    </div>
</div>

</reply>