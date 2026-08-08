<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\MaterialRequestStatus;
use App\Helper\ModelAction;
use App\Helper\RequestVisibility;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

final class CarRequest extends Model
{
    /** @use HasFactory<\Database\Factories\CarRequestFactory> */
    use ModelAction, RequestVisibility;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference', 
        'user_id', 
        'gm_approval_id', 
        'gm_comment', 
        'gm_approval_date', 
        'director_approval_id', 
        'director_comment', 
        'director_approval_date', 
        'hod_approval_id', 
        'hod_comment', 
        'hod_approval_date', 
        'somisy_car', 
        'resident', 
        'car_type', 
        'car_number', 
        'start', 
        'end', 
        'depart_at', 
        'arrive_at', 
        'destination', 
        'company', 
        'passenger_id', 
        'car_driver_id', 
        'status', 
        'expire_at', 
        'reason', 
        'route', 
        'company', 
        'comment',
        'next_approver_role',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {

        return $this->car_number
            ? "{$this->reference} — {$this->car_number}"
            : $this->reference;
    }

    public function getStartFormatAttribute(): string
    {
        return Carbon::parse($this->start)->format('d/m/Y');
    }

    public function getEndFormatAttribute(): string
    {
        return Carbon::parse($this->end)->format('d/m/Y');
    }

    public function getDepartFormatAttribute(): string
    {
        return Carbon::parse($this->depart_at)->format('H:i');
    }

    public function getArriveFormatAttribute(): string
    {
        return Carbon::parse($this->arrive_at)->format('H:i');
    }

    public function isExpire(): bool
    {
        return $this->end !== null && $this->end->isPast();
    }

    public function markAsExpiredIfNeeded(): void
    {
        if ($this->isExpire() && $this->status !== MaterialRequestStatus::Expired) {
            $this->update(['status' => MaterialRequestStatus::Expired]);
        }
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

    protected function casts(): array
    {
        return [
            'status' => MaterialRequestStatus::class,
            // 'end' => 'date',
        ];
    }

    protected function departAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? mb_substr($value, 0, 5) : null,
        );
    }

    protected function arriveAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? mb_substr($value, 0, 5) : null,
        );
    }

    protected function end(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
            set: fn ($value) => $value ?: null, // pour éviter erreur si champ vide
        );
    }
}
