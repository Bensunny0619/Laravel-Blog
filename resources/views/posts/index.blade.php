@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Posts') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8 px-4">
        @auth
            <div class="mb-6 flex justify-end">
                <a href="{{ route('posts.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-700 transition">
                    + New Post
                </a>
            </div>
        @endauth

        <div class="space-y-8">
            @foreach($posts as $post)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border border-gray-100">
                    <h3 class="text-2xl font-semibold mb-2 text-gray-800">
                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    </h3>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                        By {{ $post->user->name ?? 'Unknown' }}
                        • {{ $post->created_at->diffForHumans() }}
                    </div>
                    @if ($post->image)
                        <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="rounded-md mb-4">
                    @endif
                    <p class="text-gray-700 leading-relaxed">{{ Str::limit($post->body, 160) }}</p>

                    <div class="mt-4 flex items-center gap-4">
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
                        <div class="mt-2 flex gap-2">
                            <a href="{{ route('posts.edit', $post) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    @endauth
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
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
