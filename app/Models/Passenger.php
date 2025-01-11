<?php

namespace App\Models;

use App\Helper\DateFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passenger extends Model
{
    /** @use HasFactory<\Database\Factories\PassengerFactory> */
    use DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['car_request_id', 'name', 'contact'];

    /**
     * Get the carRequest that owns the Passenger
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carRequest(): BelongsTo
    {
        return $this->belongsTo(CarRequest::class);
    }
}
