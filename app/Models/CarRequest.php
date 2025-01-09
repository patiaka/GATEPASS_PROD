<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarRequest extends Model
{
    /** @use HasFactory<\Database\Factories\CarRequestFactory> */
    use HasFactory;

    /**
     * Get all of the carddivers for the CarRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cardivers(): HasMany
    {
        return $this->hasMany(CarDriver::class);
    }
}
