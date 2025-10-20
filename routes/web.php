<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::get('/', fn() => redirect()->route('posts.index'));

// 🧭 Public Routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->where('post', '[0-9]+')
    ->name('posts.show');

// 🔒 Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    Route::get('/my-posts', function () {
        $posts = Post::where('user_id', auth()->id())->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    })->name('posts.mine');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        $allPosts = \App\Models\Post::latest()->paginate(20);
        $myPosts = \App\Models\Post::where('user_id', $user->id)->latest()->paginate(20);
        return view('dashboard', compact('user', 'allPosts', 'myPosts'));
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
   Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
