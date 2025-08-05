<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['name', 'email', 'role', 'position', 'compagny', 'department'];
    }

    public function array(): array
    {
        return [
            // 'name' => 'test',
            // 'email' => 'test@test.com',
            // 'role' => 'test',
            // 'position' => 'test',
            // 'compagny' => 'test',
            // 'department' => 'test',
        ]; // Pas de données, juste les en-têtes
    }
}
