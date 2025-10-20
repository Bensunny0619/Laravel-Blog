<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        // Force Faker to use English
        $this->faker->locale = 'en_US';

        $topics = [
            'Technology', 'Travel', 'Health', 'Food', 'Lifestyle',
            'Education', 'Science', 'Business', 'Art', 'Sports'
        ];

        $topic = $this->faker->randomElement($topics);

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'title' => ucfirst($this->faker->catchPhrase() . " about $topic"),
              'body'    => collect(range(1, 4))
                            ->map(fn() => $this->faker->realTextBetween(200, 300))
                            ->implode("\n\n"),
            'image' => "https://picsum.photos/seed/" . uniqid() . "/800/400",
        ];
    }
}
