<?php

declare(strict_types=1);

namespace App\Enum;

enum MaterialRequestStatus: string
{
    case Pending = 'Pending';
    case Progress = 'Progressing';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}
