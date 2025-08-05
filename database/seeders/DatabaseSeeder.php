<?php

namespace Database\Seeders;

use App\Models\Compagnie;
use App\Models\Department;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Department::factory(5)->create();
        User::factory(10)->create();
        User::factory()->create(['email' => 'admin@gmail.com', 'role' => "Administrator", 'change_password' => true]);
        // Department::factory(5)->create();
    }
}
