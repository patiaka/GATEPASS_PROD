<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper\ModelAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

final class CarRequest extends Model
{
    /** @use HasFactory<\Database\Factories\CarRequestFactory> */
    use ModelAction;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['reference', 'user_id', 'gm_approval_id', 'gm_comment', 'gm_approval_date', 'hod_approval_id', 'hod_comment', 'hod_approval_date', 'somisy_car', 'resident', 'expatriate', 'licence', 'car_type', 'car_number', 'start', 'end', 'depart_at', 'arrive_at', 'destination', 'company', 'passenger_id', 'car_driver_id', 'status', 'expire_at', 'reason', 'route', 'company'];

    public function getStartFormatAttribute(): string
    {
        return Carbon::parse($this->start)->format('d/m/Y');
    }

    public function getEndFormatAttribute(): string
    {
        return Carbon::parse($this->end)->format('d/m/Y');
    }

    public function getDepart_atFormatAttribute(): string
    {
        return Carbon::parse($this->depart_at)->format('H:i');
    }

    public function getArrive_atFormatAttribute(): string
    {
        return Carbon::parse($this->arrive_at)->format('H:i');
    }

    /**
     * Get all of the passengers for the MaterialRequest
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * Get all of the car_drivers for the MaterialRequest
     */
    public function car_drivers(): HasMany
    {
        return $this->hasMany(CarDriver::class);
    }
}
