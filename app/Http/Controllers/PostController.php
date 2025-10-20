<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();

        // 🔍 Search functionality
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
        }

        // 🏷️ Filter by category (optional)
        if ($category = $request->input('category')) {
    $query->where(function ($q) use ($category) {
        $q->where('title', 'like', "%{$category}%")
          ->orWhere('body', 'like', "%{$category}%");
    });
}


        $posts = $query->latest()->paginate(20);

        // ⭐ Popular posts
        $popularPosts = Post::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(5)
            ->get();

        // 💬 Recent comments
        $recentComments = \App\Models\Comment::latest()
            ->take(5)
            ->with('post', 'user')
            ->get();

        // 🏷️ Categories (based on topics used in factory)
        $categories = [
            'Technology', 'Travel', 'Health', 'Food', 'Lifestyle',
            'Education', 'Science', 'Business', 'Art', 'Sports'
        ];

        // 🧑‍💻 Top Authors (based on number of posts)
        $topAuthors = \App\Models\User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();

        // 📅 Archive (month/year)
        $archives = Post::selectRaw('YEAR(created_at) year, MONTH(created_at) month, COUNT(*) post_count')
            ->groupBy('year', 'month')
            ->orderByRaw('MIN(created_at) desc')
            ->get();

        return view('posts.index', compact(
            'posts',
            'popularPosts',
            'recentComments',
            'categories',
            'topAuthors',
            'archives'
        ));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'body'  => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
        }

        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Post created.');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'body'  => 'required',
        ]);

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }
}
