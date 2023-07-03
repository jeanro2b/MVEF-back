<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hebergement>
 */
class HebergementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'long_title' => Str::random(35),
            'city' => Str::random(10),
            'description' => Str::random(35),
            'code' => Str::random(10),
            'pImage' => 'images/destination.png',
            'sImage' => 'images/destination.png',
            'tImage' => 'images/destination.png',
            'price' => fake()->numberBetween(50, 200),

            'couchage' => 1,
            'destination_id' => 1,
            'type_id' => 1,
        ];
    }
}
