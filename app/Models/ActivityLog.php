<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ActivityLog extends Model
{
    protected $fillable = [
        'subject_type',
        'subject_id',
        'subject_ref',
        'event',
        'causer_id',
        'causer_name',
        'changes',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }
}
