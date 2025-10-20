<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\CommentController;


// Redirect homepage to posts
Route::get('/', function () {
    $posts = Post::latest()->paginate(20);
    return view('welcome', compact('posts'));
});

// Public routes (anyone can view posts)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->where('post', '[0-9]+')
    ->name('posts.show');

// Authenticated routes (only logged-in users)
Route::middleware(['auth'])->group(function () {

    // All other CRUD routes (create, store, edit, update, destroy)
    Route::resource('posts', PostController::class)->except(['index', 'show']);

    // My posts
    Route::get('/my-posts', function () {
        $posts = \App\Models\Post::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('posts.index', compact('posts'));
    })->name('posts.mine');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Comment
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');


//Like
Route::post('/posts/{post}/like', [App\Http\Controllers\LikeController::class, 'toggle'])->name('posts.like');


// Dashboard (protected)
Route::get('/dashboard', function () {
    $user = auth()->user();
    $allPosts = Post::latest()->paginate(20);
    $myPosts = Post::where('user_id', $user->id)->latest()->paginate(20);

    return view('dashboard', compact('user', 'allPosts', 'myPosts'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Authentication routes (login, register, etc.)
require __DIR__ . '/auth.php';
