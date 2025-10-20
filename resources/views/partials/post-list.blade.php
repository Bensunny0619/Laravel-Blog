@php use Illuminate\Support\Str; @endphp

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
        </div>
    @endforeach
@else
    <div class="text-center text-gray-600 py-10">
        <p class="text-lg font-semibold">No posts found.</p>
    </div>
@endif
