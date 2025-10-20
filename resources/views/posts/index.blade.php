@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Posts') }}
            </h2>

            {{-- 🔍 Search Bar --}}
            <form method="GET" action="{{ route('posts.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search posts..."
                       class="border border-gray-300 rounded px-3 py-1 focus:ring focus:ring-blue-200">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                    Search
                </button>
            </form>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- MAIN POSTS AREA --}}
        <div class="md:col-span-2 bg-white p-6 rounded shadow">
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
                        {{-- Like Button --}}
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
                        @else
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in to like</a>
                        @endauth

                        {{-- Comment Button --}}
                        <a href="{{ route('posts.show', $post) }}#comments" class="flex items-center space-x-2 text-blue-600 hover:underline">
                            💬 <span>Comment</span>
                        </a>
                        <span class="text-gray-600">
                            {{ $post->comments->count() }} {{ Str::plural('comment', $post->comments->count()) }}
                        </span>
                    </div>

                    @auth
                        @if($post->user_id === auth()->id())
                            <div class="mt-2">
                                <a href="{{ route('posts.edit', $post) }}" class="text-yellow-600 hover:underline">Edit</a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            @endforeach

            {{ $posts->links() }}
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-6">
            {{-- ⭐ Popular Posts --}}
            <div class="bg-white p-4 rounded shadow">
                <h4 class="font-semibold text-lg mb-3 border-b pb-2">Popular Posts</h4>
                @foreach($popularPosts as $p)
                    <div class="mb-3">
                        <a href="{{ route('posts.show', $p) }}" class="text-blue-600 hover:underline">
                            {{ Str::limit($p->title, 50) }}
                        </a>
                        <p class="text-sm text-gray-500">❤️ {{ $p->likes_count }} likes</p>
                    </div>
                @endforeach
            </div>

            {{-- 💬 Recent Comments --}}
            <div class="bg-white p-4 rounded shadow">
                <h4 class="font-semibold text-lg mb-3 border-b pb-2">Recent Comments</h4>
                @foreach($recentComments as $comment)
                    <div class="mb-3">
                        <p class="text-sm text-gray-700">
                            <strong>{{ $comment->user->name ?? 'Guest' }}</strong> on 
                            <a href="{{ route('posts.show', $comment->post) }}" class="text-blue-600 hover:underline">
                                {{ Str::limit($comment->post->title, 40) }}
                            </a>
                        </p>
                        <p class="text-gray-600 text-sm">{{ Str::limit($comment->body, 60) }}</p>
                    </div>
                @endforeach
            </div>

            {{-- 🧠 About Section --}}
            <div class="bg-white p-4 rounded shadow">
                <h4 class="font-semibold text-lg mb-3 border-b pb-2">About This Blog</h4>
                <p class="text-gray-700 text-sm leading-relaxed">
                    Welcome to our Laravel-powered blog — a simple, clean space to share ideas on technology, design, and life.
                </p>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = form.getAttribute('data-post-id');
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.querySelector('.like-count-' + postId).textContent = data.likes + ' ' + (data.likes === 1 ? 'like' : 'likes');
                form.querySelector('button').innerHTML = data.liked ? '❤️ <span>Unlike</span>' : '🤍 <span>Like</span>';
            });
        });
    });
    </script>
</x-app-layout>
