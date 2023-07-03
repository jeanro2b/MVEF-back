<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Period>
 */
class PeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start' => '2023-05-12',
            'end' => '2023-05-19',
            'name' => Str::random(5),
            'mail' => fake()->unique()->safeEmail(),
            'phone' => '06 06 06 06 06',
            'number' => 4,

            'planning_id' => 1,
        ];
    }
}
