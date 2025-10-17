<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->count(3)->create(['role' => 'shelter']);
        \App\Models\User::factory()->count(5)->create(['role' => 'adopter']);
        \App\Models\Pet::factory()->count(10)->create();
    }
}
