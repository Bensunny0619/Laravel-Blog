<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const postId = form.dataset.postId;
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });
            const data = await res.json();
            const countEl = document.querySelector(`.like-count-${postId}`);
            if (countEl) {
                countEl.textContent = `${data.likes_count} like${data.likes_count === 1 ? '' : 's'}`;
            }
            form.querySelector('button').innerHTML = data.liked
                ? '❤️ <span>Unlike</span>'
                : '🤍 <span>Like</span>';
        });
    });
});
</script>

    <body class="bg-gray-100">
        @include('layouts.navigation')
        <div class="min-h-screen py-6">
            {{ $slot }}
        </div>
    </body>
</html>
