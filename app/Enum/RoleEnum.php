<?php

declare(strict_types=1);

namespace App\Enum;

enum RoleEnum: string
{
    case ADMIN = 'Administrator';
    case HOD = 'Head of Department';
    case GM = 'General Manager';
    case DIRECTOR = 'Director';
    case USER = 'User';
    case Security = 'Security';

    public static function getValue($value): string
    {
        return match ($value) {
            self::ADMIN => 'Administrator',
            self::HOD => 'Head of Department',
            self::GM => 'General Manager',
            self::DIRECTOR => 'Director',
            self::Security => 'Security',
            self::USER => 'User',
        };
    }

    public static function all(): array
    {
        return array_map(function (self $role) {
            return [
                'id' => $role->value,
                'name' => $role->value,
            ];
        }, self::cases());
    }
}
