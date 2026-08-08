<?php

declare(strict_types=1);

namespace App\Helper;

use App\Enum\RoleEnum;
use Illuminate\Support\Collection;

trait HasRoles
{
    public function getEffectiveRole(): RoleEnum
    {
        $first = $this->roles()->first() ?? $this->role?->value;

        return RoleEnum::from($first);
    }

    /**
     * Liste des rôles de l'utilisateur (pour l'UI/les formulaires).
     *
     * @return array<int, string>
     */
    public function currentRoles(): array
    {
        return $this->roles()->all();
    }

    public function delegateRole(string $role): void
    {
        $this->update(['delegated_role' => $role]);
    }

    public function revokeDelegatedRole(): void
    {
        $this->update(['delegated_role' => null]);
    }

    public function hasRole(string $role): bool
    {
        // return match ($role) {
        //     RoleEnum::ADMIN->value => $this->isAdmin(),
        //     RoleEnum::GM->value => $this->isGm(),
        //     RoleEnum::DIRECTOR->value => $this->isDirector(),
        //     RoleEnum::HOD->value => $this->isHod(),
        //     RoleEnum::Security->value => $this->isSecurity(),
        //     RoleEnum::USER->value => $this->isUser(),
        //     default => false,
        // };

        return $this->roles()->contains($role);
    }

    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function isAdmin(): bool
    {
        // return $this->getEffectiveRole() === RoleEnum::ADMIN;
        return $this->roles()->contains(RoleEnum::ADMIN->value);
    }

    public function isSecurity(): bool
    {
        return $this->roles()->contains(RoleEnum::Security->value);
    }

    public function isUser(): bool
    {
        return $this->roles()->contains(RoleEnum::USER->value);
    }

    public function isHod(): bool
    {
        // return $this->getEffectiveRole() === RoleEnum::HOD;
        return $this->roles()->contains(RoleEnum::HOD->value);
    }

    public function isDirector(): bool
    {
        // return $this->getEffectiveRole() === RoleEnum::DIRECTOR;
        return $this->roles()->contains(RoleEnum::DIRECTOR->value);
    }

    public function isGm(): bool
    {
        // return $this->getEffectiveRole() === RoleEnum::GM;
        return $this->roles()->contains(RoleEnum::GM->value);
    }

    public function isApprover(): bool
    {
        return $this->isHod() || $this->isDirector() || $this->isGm();
    }

    public function isSimpleUser(): bool
    {
        return $this->isUser() && $this->roles()->count() === 1;
    }

    private function roles(): Collection
    {
        $this->loadMissing('roleAssignments');
        $pivotRoles = $this->roleAssignments->pluck('role')->filter();

        if ($pivotRoles->isNotEmpty()) {
            return $pivotRoles->unique()->values();
        }

        // Fallback rétrocompatible : users sans entrée pivot (pendant la transition)
        return collect([
            $this->role?->value,
            $this->delegated_role?->value,
        ])->filter()->unique()->values();
    }
}
