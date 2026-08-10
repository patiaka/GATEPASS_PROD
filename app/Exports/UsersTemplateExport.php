<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

final class UsersTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        // Le département et le rôle sont choisis à l'import (pas dans le fichier)
        return ['name', 'email', 'position', 'contact', 'badge_number'];
    }

    public function array(): array
    {
        // Une ligne d'exemple pour guider le remplissage
        return [
            ['John Doe', 'john.doe@example.com', 'Technician', '70000000', 'BN-0001'],
        ];
    }
}
