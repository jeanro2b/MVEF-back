<?php

namespace Database\Seeders;

use App\Models\DestinationHasService;
use Database\Factories\DestinationHasServiceFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestinationHasServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DestinationHasServiceFactory::factoryForModel(DestinationHasService::class)
        ->count(5)
        ->sequence(
            ['service_id' => 1],
            ['service_id' => 3],
            ['service_id' => 2],
            ['service_id' => 5],
            ['service_id' => 1],
        )
        ->create();
    }
}
