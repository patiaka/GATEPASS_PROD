<?php

declare(strict_types=1);

namespace App\Enum;

enum CarRequestLicenceStatus: string
{
    case MaliDL = 'Mali DL';
    case ForeignDL = 'Foreign DL';
    case IntlPermit = 'Intl Permit';
}
