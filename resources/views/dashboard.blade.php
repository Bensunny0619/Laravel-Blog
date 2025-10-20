<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👋 Welcome, {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- My Posts --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">📝 My Posts</h3>

                @if ($myPosts->count())
                    <ul class="space-y-3">
                        @foreach ($myPosts as $post)
                            <li class="border-b pb-2">
                                <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                                    {{ $post->title }}
                                </a>
                                <p class="text-sm text-gray-500">Created {{ $post->created_at->diffForHumans() }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4">{{ $myPosts->links() }}</div>
                @else
                    <p class="text-gray-500">You haven’t created any posts yet.</p>
                @endif
                <a href="{{ route('posts.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    ➕ New Post
                </a>
            </div>

            {{-- All Posts --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">🌍 All Posts</h3>

                @if ($allPosts->count())
                    <ul class="space-y-3">
                        @foreach ($allPosts as $post)
                            <li class="border-b pb-2">
                                <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                                    {{ $post->title }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    by {{ $post->user->name ?? 'Unknown' }} · {{ $post->created_at->diffForHumans() }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4">{{ $allPosts->links() }}</div>
                @else
                    <p class="text-gray-500">No posts available yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
