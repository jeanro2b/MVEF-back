<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Planning>
 */
class PlanningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'object' => Str::random(10),
            'code' => Str::random(5),
            'status' => 'En cours',
            'lit' => 'Oui',
            'toilette' => 'Oui',
            'menage' => 'Oui',

            'hebergement_id' => 1,
            'user_id' => 1,
        ];
    }
}
