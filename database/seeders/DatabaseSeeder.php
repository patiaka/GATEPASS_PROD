<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Department::factory(5)->create();
        User::factory(10)->create();
        User::factory()->create(['email' => 'admin@gmail.com', 'role' => 'Administrator']);
        User::factory()->create(['email' => 'gm@gmail.com', 'role' => 'General Manager']);
        User::factory()->create(['email' => 'hod@gmail.com', 'role' => 'Head of Department']);
        User::factory()->create(['email' => 'security@gmail.com', 'role' => 'Security']);
    }
}
