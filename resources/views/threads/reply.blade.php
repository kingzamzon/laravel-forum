<div class="mt-3 mb-3">
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

                <div>
                    

                    <form action="{{ route('favourites.replies', ['reply' => $reply->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary" {{ $reply->isFavourited() ? 'disabled' : '' }}>
                            {{ $reply->favourites_count }} Favourite</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{ $reply->body }}
        </div>
    </div>
</div>