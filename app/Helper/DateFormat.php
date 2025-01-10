<?php

declare(strict_types=1);

namespace App\Helper;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enum\MaterialRequestStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

trait DateFormat
{
    use HasFactory;

    protected function getCreatedAtAttribute(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
}
