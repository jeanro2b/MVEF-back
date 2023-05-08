<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::factory()
        ->count(5)
        ->sequence(
            ['planning_id' => 1],
            ['planning_id' => 2],
            ['planning_id' => 3],
            ['planning_id' => 1],
            ['planning_id' => 2],
        )
        ->create();
    }
}
