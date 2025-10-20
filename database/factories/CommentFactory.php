<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use App\Models\User;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'post_id' => Post::inRandomOrder()->first()->id ?? Post::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'body'    => fake()->realTextBetween(50, 150, 2), // short English comment
        ];
    }
}
