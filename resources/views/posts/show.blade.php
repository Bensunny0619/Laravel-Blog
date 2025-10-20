<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded shadow">
        {{-- Post Details --}}
        <p class="text-gray-600 text-sm mb-2">
            By {{ $post->user->name ?? 'Unknown' }}
        </p>

        <div class="text-gray-800">
            {{ $post->body }}
        </div>

        {{-- Like Button --}}
        <div class="mt-4 flex items-center">
            @auth
                <form action="{{ route('posts.like', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center space-x-2">
                        @if ($post->likes->where('user_id', auth()->id())->count())
                            ❤️ <span>Unlike</span>
                        @else
                            🤍 <span>Like</span>
                        @endif
                    </button>
                </form>
            @else
                <p class="text-gray-500">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a> to like this post.
                </p>
            @endauth

            <span class="ml-4 text-gray-600">
                {{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}
            </span>
        </div>

        <div class="mt-4">
            <a href="{{ route('posts.index') }}" class="text-blue-600 hover:underline">← Back to all posts</a>
        </div>

        {{-- Comments Section --}}
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-4">💬 Comments</h3>

            {{-- Comment Form (for logged-in users only) --}}
            @auth
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-6">
                    @csrf
                    @if ($post->image)
    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="rounded-md mb-4">
@endif

                    <textarea 
                        name="body" 
                        rows="3" 
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Write a comment..." 
                        required
                    ></textarea>

                    <button 
                        type="submit" 
                        class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                    >
                        Post Comment
                    </button>
                    
                </form>
            @else
                <p class="text-gray-500 mb-4">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a> to post a comment.
                </p>
            @endauth

            {{-- Display Existing Comments --}}
            @forelse ($post->comments as $comment)
                <div class="border-t border-gray-200 py-2">
                    <p>{{ $comment->body }}</p>
                    <small class="text-gray-500">
                        By {{ $comment->user->name ?? 'Anonymous' }} • {{ $comment->created_at->diffForHumans() }}
                    </small>
                </div>
            @empty
                <p class="text-gray-500">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
