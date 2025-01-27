<?php

declare(strict_types=1);

namespace App\Enum;

enum MaterialRequestStatus: string
{
    case Pending = 'Pending';
    case Progress = 'Progress';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Expired = 'Expired';
}
