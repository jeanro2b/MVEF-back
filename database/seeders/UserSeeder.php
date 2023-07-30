<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
        ->count(3)
        ->sequence(
            ['role' => 'ce'],
            ['role' => 'admin'],
            ['role' => 'user'],
            ['email' => 'ce@ce.com'],
            ['email' => 'admin@admin.com'],
            ['email' => 'user@user.com'],
        )
        ->create();
    }
}
