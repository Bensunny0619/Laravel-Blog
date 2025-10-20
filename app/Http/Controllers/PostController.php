<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();

        if ($search = $request->input('search')) {
            $query->where(fn($q) => 
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
            );
        }

        if ($category = $request->input('category')) {
            $query->where(fn($q) => 
                $q->where('title', 'like', "%{$category}%")
                  ->orWhere('body', 'like', "%{$category}%")
            );
        }

        $posts = $query->with(['user', 'likes', 'comments'])->latest()->paginate(20);

        $popularPosts = Post::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(5)
            ->get();

        $recentComments = Comment::with('post', 'user')
            ->latest()
            ->take(5)
            ->get();

        $categories = ['Technology', 'Travel', 'Health', 'Food', 'Lifestyle', 'Education', 'Science', 'Business', 'Art', 'Sports'];

        $topAuthors = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();

        $archives = Post::selectRaw('YEAR(created_at) year, MONTH(created_at) month, COUNT(*) post_count')
            ->groupBy('year', 'month')
            ->orderByRaw('MIN(created_at) desc')
            ->get();

        return view('posts.index', compact('posts', 'popularPosts', 'recentComments', 'categories', 'topAuthors', 'archives'));
    }

    public function search(Request $request)
    {
        $query = $request->get('search', '');

        $posts = Post::with(['user', 'likes', 'comments'])
            ->when($query, fn($q) =>
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('body', 'like', "%{$query}%")
            )
            ->latest()
            ->get();

        $html = view('posts._list', compact('posts'))->render();

        return response()->json(['html' => $html]);
    }
    
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // 1️⃣ Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2️⃣ Handle file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public');
        }

        // 3️⃣ Create the post
        Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        // 4️⃣ Redirect back with success message
        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }
}
