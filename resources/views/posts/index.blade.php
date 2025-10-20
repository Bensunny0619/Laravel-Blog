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
                <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                       placeholder="Search posts..."
                       class="border border-gray-300 rounded px-3 py-1 focus:ring focus:ring-blue-200">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                    Search
                </button>
            </form>
        </div>
    </x-slot>

    {{-- Main layout with 3 columns --}}
    <div class="max-w-7xl mx-auto mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">

        {{-- LEFT SIDEBAR --}}
        <div class="space-y-6">
            {{-- 🏷️ Categories --}}
            <div class="bg-white p-4 rounded shadow">
                <h4 class="font-semibold text-lg mb-3 border-b pb-2">Categories</h4>
                @foreach($categories as $cat)
                    <a href="{{ route('posts.index', ['category' => $cat]) }}"
                       class="block text-blue-600 hover:underline mb-1">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            {{-- 🧑‍💻 Top Authors --}}
            <div class="bg-white p-4 rounded shadow">
                <h4 class="font-semibold text-lg mb-3 border-b pb-2">Top Authors</h4>
                @foreach($topAuthors as $author)
                    <div class="flex items-center gap-2 mb-2">
                        <img src="{{ $author->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) }}" 
                             class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-medium text-gray-800">{{ $author->name }}</p>
                            <p class="text-sm text-gray-500">{{ $author->posts_count }} posts</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 📅 Archive --}}
            <div class="bg-white p-4 rounded shadow">
                <h4 class="font-semibold text-lg mb-3 border-b pb-2">Archive</h4>
                @foreach($archives as $archive)
                    <p class="text-gray-700 text-sm mb-1">
                        {{ \Carbon\Carbon::create($archive->year, $archive->month)->format('F Y') }}
                        ({{ $archive->post_count }})
                    </p>
                @endforeach
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="md:col-span-2 bg-white p-6 rounded shadow">
            <div id="post-list">
                @if($posts->count() > 0)
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
                        </div>
                    @endforeach

                    {{ $posts->links() }}
                @else
                    <div class="text-center text-gray-600 py-10">
                        <p class="text-lg font-semibold">No posts found.</p>
                        <p class="text-sm">Try a different search term or clear the filter.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
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

    {{-- 🔄 Dynamic Search --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById("search-input");
        const postList = document.getElementById("post-list");
        let typingTimer;
        const delay = 400;

        searchInput.addEventListener("input", function() {
            clearTimeout(typingTimer);
            const query = this.value.trim();

            typingTimer = setTimeout(() => {
                if (query === "") {
                    // Reset search without refreshing page
                    fetch(`{{ route('posts.search') }}`)
                        .then(res => res.json())
                        .then(data => {
                            postList.innerHTML = data.html || `<p class='text-center text-gray-600 py-10'>No posts found.</p>`;
                        });
                    return;
                }

                fetch(`{{ route('posts.search') }}?search=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        postList.innerHTML = data.html || `<p class='text-center text-gray-600 py-10'>No posts found.</p>`;
                    })
                    .catch(err => console.error("Search error:", err));
            }, delay);
        });
    });
    </script>
</x-app-layout>
