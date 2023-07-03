<?php

namespace Database\Seeders;

use App\Models\Equipements;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Equipements::factory()
            ->count(5)
            ->sequence(
                ['text' => 'Kitchenette équipée'],
                ['text' => "Salle d'eau + WC"],
                ['text' => "Salle d'eau + WC"],
                ['text' => "Salle d'eau + WC"],
                ['text' => "Salle d'eau + WC"],
            )
            ->sequence(
                ['hebergement_id' => 1],
                ['hebergement_id' => 2],
                ['hebergement_id' => 3],
                ['hebergement_id' => 4],
                ['hebergement_id' => 5],
            )
            ->create();
    }
}
