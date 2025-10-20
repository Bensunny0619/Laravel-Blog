@php use Illuminate\Support\Str; @endphp

@if($posts->count())
    @foreach($posts as $post)
        <div class="border-b border-gray-200 pb-4 mb-4">
            <h3 class="text-lg font-bold">
                <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                    {{ $post->title }}
                </a>
            </h3>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                @if ($post->user && $post->user->profile_photo)
                    <img src="{{ $post->user->profile_photo }}" alt="Profile" class="w-6 h-6 rounded-full">
                @endif
                <span>By {{ $post->user->name ?? 'Unknown' }}</span>
            </div>

            @if ($post->image)
                @if(Str::startsWith($post->image, ['http://', 'https://']))
                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="rounded-md my-2 w-full max-w-md mx-auto shadow">
                @else
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="rounded-md my-2 w-full max-w-md mx-auto shadow">
                @endif
            @endif

            <p class="mt-2 text-gray-800">{{ Str::limit($post->body, 150) }}</p>

            <div class="mt-3 flex items-center gap-4">
                @auth
                    <form action="{{ route('posts.like', $post) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2">
                            @if ($post->likes->where('user_id', auth()->id())->count())
                                ❤️ <span>Unlike</span>
                            @else
                                🤍 <span>Like</span>
                            @endif
                        </button>
                    </form>
                    <span class="text-gray-600 like-count-{{ $post->id }}">
                        {{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}
                    </span>

                    <a href="{{ route('posts.show', $post) }}#comments" class="flex items-center space-x-2 text-blue-600 hover:underline">
                        💬 <span>Comment</span>
                    </a>
                    <span class="text-gray-600">
                        {{ $post->comments->count() }} {{ Str::plural('comment', $post->comments->count()) }}
                    </span>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in to like/comment</a>
                @endauth
            </div>
        </div>
    @endforeach
@else
    <div class="text-center text-gray-600 py-10">
        <p class="text-lg font-semibold">No posts found.</p>
        <p class="text-sm">Try a different search or clear your filters.</p>
    </div>
@endif
