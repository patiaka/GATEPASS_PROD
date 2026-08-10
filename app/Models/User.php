<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\RoleEnum;
use App\Helper\DateFormat;
use App\Helper\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    use DateFormat, HasRoles, Notifiable;

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
        'delegated_role',
        'contact',
        'badge_number',
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

    protected $appends = ['full_name'];

    /**
     * Get the department that owns the User
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Rôles de l'utilisateur (multi-rôles via la table pivot role_user).
     */
    public function roleAssignments(): HasMany
    {
        return $this->hasMany(RoleUser::class);
    }

    /**
     * Remplace l'ensemble des rôles de l'utilisateur.
     *
     * @param  array<int, string>  $roles
     */
    public function syncRoles(array $roles): void
    {
        $roles = collect($roles)->filter()->unique()->values();

        $this->roleAssignments()->delete();
        foreach ($roles as $role) {
            $this->roleAssignments()->create(['role' => $role]);
        }
        $this->setRelation('roleAssignments', $this->roleAssignments()->get());

        // Rétrocompat : on garde users.role rempli avec le rôle principal (le 1er)
        if ($roles->isNotEmpty()) {
            $this->updateQuietly(['role' => $roles->first()]);
        }
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
     * Get all of the car_drivers for the User
     */
    public function car_drivers(): HasMany
    {
        return $this->hasMany(CarDriver::class);
    }

    /**
     * Get all of the passengers for the User
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * Get all of the hod_approvals for the User
     */
    public function hod_approvals(): HasMany
    {
        return $this->hasMany(MaterialRequest::class, 'hod_approval_id');
    }

    /**
     * Get all of the director_approvals for the User
     */
    public function director_approvals(): HasMany
    {
        return $this->hasMany(MaterialRequest::class, 'director_approval_id');
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
     * Get all of the director_approvals for the User
     */
    public function director_car_approvals(): HasMany
    {
        return $this->hasMany(CarRequest::class, 'director_approval_id');
    }

    /**
     * Get all of the gm_approvals for the User
     */
    public function gm_car_approvals(): HasMany
    {
        return $this->hasMany(CarRequest::class, 'gm_approval_id');
    }

    public function getFullNameAttribute(): string
    {
        $badge = $this->getAttribute('badge_number');

        return $badge
            ? "{$badge} — {$this->name}"
            : $this->name;
    }

    /**
     * @param MaterialRequest|CarRequest $request
     */
    public function canApprove($request): bool
    {
        // L'approbation suit l'ÉTAPE courante de la demande (next_approver_role).
        // L'utilisateur peut approuver s'il possède le rôle de cette étape et que
        // celle-ci n'est pas déjà validée. Basé sur l'étape (et non sur une
        // cascade de if), c'est correct pour un utilisateur multi-rôles.
        $stage = $request->next_approver_role;

        if ($stage === null) {
            return false; // demande déjà entièrement traitée
        }

        return match ($stage) {
            RoleEnum::HOD->value => $this->isHod() && ! $request->isHodApproved(),
            RoleEnum::DIRECTOR->value => $this->isDirector() && ! $request->isDirectorApproved(),
            RoleEnum::GM->value => $this->isGm() && ! $request->isGmApproved(),
            default => false,
        };
    }

    protected ?int $awaitingApprovalCountMemo = null;

    /**
     * Nombre de demandes (véhicule + matériel) en attente de l'approbation de
     * cet utilisateur, selon son étape (HOD / Directeur / GM). Mémoïsé pour la
     * requête courante (utilisé par la cloche et le badge de la sidebar).
     */
    public function awaitingApprovalCount(): int
    {
        if ($this->awaitingApprovalCountMemo !== null) {
            return $this->awaitingApprovalCountMemo;
        }

        if (! $this->isApprover()) {
            return $this->awaitingApprovalCountMemo = 0;
        }

        return $this->awaitingApprovalCountMemo = CarRequest::query()->awaitingApprovalBy($this)->count()
            + MaterialRequest::query()->awaitingApprovalBy($this)->count();
    }

    public function isActive(): bool
    {
        return $this->status === true;
    }

    public function isBlocked(): bool
    {
        return $this->status === false;
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
            'delegated_role' => RoleEnum::class,
            'status' => 'boolean',
            'change_password' => 'boolean'
        ];
    }
}
