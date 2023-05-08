<?php

namespace Database\Seeders;

use App\Models\Planning;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Planning::factory()
        ->count(5)
        ->sequence(
            ['hebergement_id' => 1],
            ['hebergement_id' => 2],
            ['hebergement_id' => 3],
            ['hebergement_id' => 1],
            ['hebergement_id' => 4],
        )
        ->create();
    }
}
