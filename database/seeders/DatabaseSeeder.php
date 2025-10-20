<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 users
        User::factory()->count(10)->create();

        // Create 50 posts with English titles, text, and images
        Post::factory()->count(50)->create()->each(function ($post) {
            // Add 2–6 comments per post
            Comment::factory()->count(rand(2, 6))->create([
                'post_id' => $post->id,
                'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            ]);
        });
    }
}
