<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Profile Settings</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-6">
        @if (session('status'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('patch')

            {{-- Name --}}
            <div>
                <label class="block text-gray-700 font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
                @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Profile Photo --}}
            <div>
                <label class="block text-gray-700 font-medium">Profile Photo</label>
                <div class="flex items-center gap-4 mt-2">
                    <img id="preview-image"
                        src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                        alt="Profile Photo" class="w-16 h-16 rounded-full object-cover border">
                    <input type="file" name="profile_photo" accept="image/*" onchange="previewImage(event)">
                </div>
                @error('profile_photo') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-gray-700 font-medium">New Password (optional)</label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
                <input type="password" name="password_confirmation" placeholder="Confirm password"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-2 focus:ring focus:ring-blue-200">
                @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('preview-image').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>
