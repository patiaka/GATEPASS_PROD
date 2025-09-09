<?php

declare(strict_types=1);

namespace App\Helper;

use App\Enum\RoleEnum;

trait HasRoles
{
    public function getEffectiveRole(): RoleEnum
    {
        return $this->delegated_role ?? $this->role;
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
        return match ($role) {
            RoleEnum::ADMIN->value    => $this->isAdmin(),
            RoleEnum::GM->value       => $this->isGm(),
            RoleEnum::HOD->value      => $this->isHod(),
            RoleEnum::Security->value => $this->isSecurity(),
            RoleEnum::USER->value     => $this->isUser(),
            default => false,
        };
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
        return $this->role === RoleEnum::ADMIN;
    }

    public function isSecurity(): bool
    {
        return $this->role === RoleEnum::Security;
    }

    public function isUser(): bool
    {
        return $this->role === RoleEnum::USER;
    }

    public function isHod(): bool
    {
        return $this->getEffectiveRole() === RoleEnum::HOD;
    }

    public function isGm(): bool
    {
        return $this->getEffectiveRole() === RoleEnum::GM;
    }
}
