<?php

declare(strict_types=1);

namespace App\Enum;

enum RoleEnum: string
{
    case ADMIN = 'Administrator';
    case HOD = 'Head of Department';
    case GM = 'General Manager';
    case USER = 'User';
}
