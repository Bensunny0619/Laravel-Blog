<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    return Post::latest()->paginate(10);
}

public function store(Request $request)
{
    $data = $request->validate(['title'=>'required','body'=>'required']);
    $post = Post::create($data);
    return response()->json($post, 201);
}

public function create()
    {
        return view('posts.create');
    }
    
public function show(Post $post)
{
    return $post;
}

public function update(Request $request, Post $post)
{
    $data = $request->validate(['title'=>'required','body'=>'required']);
    $post->update($data);
    return response()->json($post);
}

public function destroy(Post $post)
{
    $post->delete();
    return response()->json(null, 204);
}

}
