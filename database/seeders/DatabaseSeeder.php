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
        \App\Models\Department::firstOrCreate([
            'name' => $department,
        ]);
    }

    // Compte administrateur initial — créé SANS factory/faker pour rester
    // compatible avec une installation de production (composer --no-dev).
    // Idempotent : on peut relancer le seed sans créer de doublon.
    $admin = \App\Models\User::updateOrCreate(
        ['email' => 'admin@somisy.com'],
        [
            'name' => 'Administrator',
            'poste' => 'System Administrator',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'change_password' => true,   // connexion directe (pas de changement forcé)
            'status' => true,            // compte actif
            'role' => \App\Enum\RoleEnum::ADMIN->value,
            'department_id' => \App\Models\Department::query()->min('id'),
            'contact' => '0000000000',
            'badge_number' => 'ADM001',
        ]
    );

    $admin->syncRoles([\App\Enum\RoleEnum::ADMIN->value]);
}
}
