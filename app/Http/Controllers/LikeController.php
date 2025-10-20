<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();

        // Toggle like/unlike
        $existingLike = $post->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);
        }

        // 🔧 If it's an AJAX request (fetch), return JSON
        if (request()->ajax()) {
            return response()->json([
                'likes_count' => $post->likes()->count(),
                'liked' => !$existingLike
            ]);
        }

        // 🔧 Otherwise, redirect back (normal form submission)
        return redirect()->back();
    }
}
