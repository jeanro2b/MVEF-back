<?php

namespace Database\Seeders;

use App\Models\Hebergement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HebergementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hebergement::factory()
        ->count(12)
        ->sequence(
            ['destination_id' => 1],
            ['destination_id' => 2],
            ['destination_id' => 3],
        )
        ->sequence(
            ['couchage' => 1],
            ['couchage' => 2],
            ['couchage' => 3],
            ['couchage' => 4],
            ['couchage' => 5],
            ['couchage' => 6],
        )
        ->create();
    }
}
