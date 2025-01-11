<?php

namespace App\Models;

use App\Helper\DateFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarDriver extends Model
{
    /** @use HasFactory<\Database\Factories\CarDriverFactory> */
    use DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['car_request_id', 'name', 'contact'];
    /**
     * Get the carRequest that owns the CarDriver
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carRequest(): BelongsTo
    {
        return $this->belongsTo(CarRequest::class);
    }
}
