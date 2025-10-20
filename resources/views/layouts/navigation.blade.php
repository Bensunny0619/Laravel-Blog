<nav class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <!-- Left side: Logo / Links -->
        <div class="flex items-center space-x-6">
            <a href="{{ route('dashboard') }}" class="font-bold text-xl text-blue-700 hover:text-blue-800">
                Dashboard
            </a>
            <a href="{{ route('posts.index') }}" class="text-gray-700 hover:text-blue-600">
                All Posts
            </a>

              <!-- New Post Button -->
                <a href="{{ route('posts.create') }}" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                    + New Post
                </a>
        </div>

        <!-- Right side: Auth Links -->
        <div class="flex items-center gap-4">
            @auth
                <!-- Profile section -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-gray-700 hover:text-blue-600">
                    <img class="h-8 w-8 rounded-full object-cover border"
                        src="{{ Auth::user()->profile_photo 
                            ? asset('storage/' . Auth::user()->profile_photo) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                        alt="{{ Auth::user()->name }}">
                    <span>{{ Auth::user()->name }}</span>
                </a>

              

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:underline">
                        Logout
                    </button>
                </form>
            @else
                <!-- Login & Register -->
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 mr-2">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                    Sign Up
                </a>
            @endauth
        </div>
    </div>
</nav>
