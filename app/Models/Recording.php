<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Recording extends Model
{
    /** @use HasFactory<\Database\Factories\RecordingFactory> */
    use HasFactory;

    protected $fillable = [
        'requestable_id',
        'requestable_type',
        'action',
        'decision',
        'checked_at',
        'user_id',
        'car_driver_id',
        'gate',
        'fuel_level',
        'kilometers',
        // 'destination',
    ];

    /**
     * Get the car_driver that owns the Recording
     */
    public function car_driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'car_driver_id');
    }

    /**
     * Get the user that owns the Recording
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requestable(): MorphTo
    {
        return $this->morphTo();
    }
}
