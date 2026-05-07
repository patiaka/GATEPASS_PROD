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
    // public function run(): void
    // {
    //     Department::factory(5)->create();
    //     User::factory(10)->create();
    //     User::factory()->create(['email' => 'admin@somisy.com', 'role' => 'Administrator', 'contact' => '1234567890', 'badge_number' => 'ADM001']);
    //     User::factory()->create(['email' => 'gm@somisy.com', 'role' => 'General Manager', 'contact' => '1234567891', 'badge_number' => 'GM001']);
    //     User::factory()->create(['email' => 'hod@somisy.com', 'role' => 'Head of Department', 'contact' => '1234567892', 'badge_number' => 'HOD001']);
    //     User::factory()->create(['email' => 'security@somisy.com', 'role' => 'Security', 'contact' => '1234567893', 'badge_number' => 'SEC001']);
    // }
    public function run(): void
{
    $departments = [
        'Camp and Travel',
        'Community',
        'Environment',
        'ERT',
        'Exploration',
        'Finance & Admin',
        'Geology',
        'ICT',
        'Mobile Maintenance',
        'People and Development',
        'Processing',
        'Safety',
        'Security',
        'Supply',
        'Surface Mining',
        'UG Mining',
        'SOMISY',
        'OCH',
        'Project and Engineering',
        'SSCP',
    ];

    foreach ($departments as $department) {
        \App\Models\Department::create([
            'name' => $department
        ]);
    }

    // Users
    \App\Models\User::factory()->create();

    \App\Models\User::factory()->create([
        'email' => 'admin@somisy.com',
        'role' => 'Administrator',
        'contact' => '1234567890',
        'badge_number' => 'ADM001'
    ]);
}
}
