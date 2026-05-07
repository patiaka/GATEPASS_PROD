<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CarDriver extends Model
{
    /** @use HasFactory<\Database\Factories\CarDriverFactory> */
    use DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['car_request_id', 'user_id'];

    /**
     * Get the carRequest that owns the CarDriver
     */
    public function carRequest(): BelongsTo
    {
        return $this->belongsTo(CarRequest::class);
    }

    /**
     * Get the user that owns the CarDriver
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
