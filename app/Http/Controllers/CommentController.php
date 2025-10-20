<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{public function store(Request $request, Post $post)
{
    $request->validate([
        'body' => 'required|string|max:1000',
    ]);

    $comment = new Comment();
    $comment->body = $request->body;
    $comment->user_id = Auth::id();
    $comment->post_id = $post->id;
    $comment->save();

    return redirect()->route('posts.show', $post)->with('success', 'Comment added!');
}

}



