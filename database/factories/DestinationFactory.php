<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
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
            'address' => Str::random(15),
            'phone' => '06 06 06 06 06',
            'description' => Str::random(35),
            'latitude' => Str::random(2),
            'longitude' => Str::random(2),
            'languages' => Str::random(7),
            'mail' => fake()->unique()->safeEmail(),
            'reception' => Str::random(20),
            'arrival' => '10:00',
            'departure' => '18:00',
            'map' => Str::random(35),
            'pImage' => '../images/presentation-image.jpeg',
            'sImage' => '../images/presentation-image.jpeg',
            'tImage1' => '../images/presentation-image.jpeg',
            'tImage2' => '../images/presentation-image.jpeg',
            'vehicule' => True,
            'parking' => True
        ];
    }
}
