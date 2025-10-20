<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();

        $like = Like::where('post_id', $post->id)
                    ->where('user_id', $user->id)
                    ->first();

        if ($like) {
            $like->delete(); // Unlike
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
        }

        return back();
    }
}
