<?php

namespace Database\Factories\API;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\API\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $filePath = storage_path('images');

        return [
            "user_id" => fake()->numberBetween(1,50),
            "desc" => fake()->text(),
            "photo" => fake()->image($filePath,400,300)
        ];
    }
}
