<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700">Title</label>
                        <input type="text" name="title" id="title"
                            class="w-full border border-gray-300 rounded-md p-2" required>
                    </div>

                    <div class="mb-4">
                        <label for="body" class="block text-gray-700">Body</label>
                        <textarea name="body" id="body" rows="5"
                            class="w-full border border-gray-300 rounded-md p-2" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-gray-700">Image (optional)</label>
                        <input type="file" name="image" id="image"
                            class="w-full border border-gray-300 rounded-md p-2">
                    </div>

                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Create Post
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
