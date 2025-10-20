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
                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                            {{ $post->title }}
                        </a>
                    </h3>

                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                        @if ($post->user && $post->user->profile_photo)
                            <img src="{{ $post->user->profile_photo }}" alt="Profile" class="w-6 h-6 rounded-full object-cover">
                        @endif
                        <span>By {{ $post->user->name ?? 'Unknown' }}</span>
                        <span>•</span>
                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                    </div>

                    @if ($post->image)
                        @if(Str::startsWith($post->image, ['http://', 'https://']))
                            <img src="{{ $post->image }}" alt="{{ $post->title }}" 
                                 class="rounded-lg my-4 w-full max-h-64 object-cover shadow">
                        @else
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" 
                                 class="rounded-lg my-4 w-full max-h-64 object-cover shadow">
                        @endif
                    @endif

                    <p class="text-gray-700 leading-relaxed">{{ Str::limit($post->body, 160) }}</p>

                    @auth
                        @if($post->user_id === auth()->id())
                            <div class="mt-4 flex gap-3">
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
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>
