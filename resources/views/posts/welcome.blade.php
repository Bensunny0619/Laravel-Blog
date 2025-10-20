<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏠 Latest Posts
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @foreach ($posts as $post)
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-bold mb-2">
                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                            {{ $post->title }}
                        </a>
                    </h3>
                    <p class="text-gray-600">{{ Str::limit($post->body, 120) }}</p>
                    <p class="text-sm text-gray-400 mt-2">
                        Posted {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>
            @endforeach

            <div>{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>
