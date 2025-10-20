<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Edit Post
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Body</label>
                        <textarea name="body" rows="5" class="w-full border-gray-300 rounded-md" required>{{ old('body', $post->body) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Image (optional)</label>
                        <input type="file" name="image" accept="image/*" onchange="previewImage(event)" class="w-full border-gray-300 rounded-md">

                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="mt-3 rounded-md" style="max-width: 300px;">
                        @endif

                        <img id="preview" src="#" alt="Preview" class="mt-3 rounded-md" style="max-width: 300px; display:none;">
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md mr-2">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
