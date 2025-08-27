<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\RoleEnum;
use App\Helper\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use DateFormat, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'change_password',
        'role',
        'poste',
        'department_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === RoleEnum::ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === RoleEnum::USER;
    }

    public function isHod(): bool
    {
        return $this->role === RoleEnum::HOD;
    }

    public function isGm(): bool
    {
        return $this->role === RoleEnum::GM;
    }

    /**
     * Get the department that owns the User
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all of the material_requests for the User
     */
    public function material_requests(): HasMany
    {
        return $this->hasMany(MaterialRequest::class);
    }

    /**
     * Get all of the car_requests for the User
     */
    public function car_requests(): HasMany
    {
        return $this->hasMany(CarRequest::class);
    }

    /**
     * Get all of the hod_approvals for the User
     */
    public function hod_approvals(): HasMany
    {
        return $this->hasMany(MaterialRequest::class, 'hod_approval_id');
    }

    /**
     * Get all of the gm_approvals for the User
     */
    public function gm_approvals(): HasMany
    {
        return $this->hasMany(MaterialRequest::class, 'gm_approval_id');
    }

    /**
     * Get all of the hod_approvals for the User
     */
    public function hod_car_approvals(): HasMany
    {
        return $this->hasMany(CarRequest::class, 'hod_approval_id');
    }

    /**
     * Get all of the gm_approvals for the User
     */
    public function gm_car_approvals(): HasMany
    {
        return $this->hasMany(CarRequest::class, 'gm_approval_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => RoleEnum::class,
        ];
    }
}
