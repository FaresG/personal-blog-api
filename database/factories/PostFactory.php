<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle;
        return [
            'user_id' => 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->realTextBetween(10, 30),
            'content' => fake()->realText()
        ];
    }
}
