<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::factory()
        ->count(5)
        ->sequence(
            ['type' => 'Appartement'],
            ['type' => 'Mobil Home'],
            ['type' => 'Villa'],
            ['type' => 'Chalet'],
            ['type' => 'Hébergement insolite'],
        )
        ->create();
    }
}
