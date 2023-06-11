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
            'city' => Str::random(10),
            'description' => Str::random(35),
            'code' => Str::random(10),
            'image' => 'images/destination.png',

            'destination_id' => 1,
            'type_id' => 1,
        ];
    }
}
