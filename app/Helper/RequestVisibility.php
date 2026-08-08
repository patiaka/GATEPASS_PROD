<?php

declare(strict_types=1);

namespace App\Helper;

use App\Enum\MaterialRequestStatus;
use App\Enum\RoleEnum;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Source unique de vérité pour la visibilité des demandes par rôle.
 * Utilisé par le Dashboard ET les pages de listing pour que les
 * compteurs correspondent toujours aux listes.
 */
trait RequestVisibility
{
    /**
     * Ce que l'utilisateur peut voir, tous ses rôles cumulés (OR).
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            // Tout le monde voit ses propres demandes (un User simple ne voit que ça)
            $q->where('user_id', $user->id);

            if ($user->isGm()) {
                $q->orWhere('next_approver_role', RoleEnum::GM->value)
                    ->orWhere('gm_approval_id', $user->id);
            }

            if ($user->isDirector()) {
                $q->orWhereIn('user_id', self::directedUserIds($user))
                    ->orWhere('director_approval_id', $user->id);
            }

            if ($user->isHod()) {
                $q->orWhereIn('user_id', self::departmentUserIds($user))
                    ->orWhere('hod_approval_id', $user->id);
            }

            if ($user->isSecurity()) {
                $q->orWhere('status', MaterialRequestStatus::Approved);
            }
        });
    }

    /**
     * Demandes en attente de l'action de l'utilisateur (approbateurs).
     */
    public function scopeAwaitingApprovalBy(Builder $query, User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            // Clause neutre pour garder un groupe valide quel que soit le rôle
            $q->whereRaw('1 = 0');

            if ($user->isGm()) {
                $q->orWhere('next_approver_role', RoleEnum::GM->value);
            }

            if ($user->isDirector()) {
                $q->orWhere(function ($sub) use ($user) {
                    $sub->where('next_approver_role', RoleEnum::DIRECTOR->value)
                        ->whereIn('user_id', self::directedUserIds($user));
                });
            }

            if ($user->isHod()) {
                $q->orWhere(function ($sub) use ($user) {
                    $sub->where('next_approver_role', RoleEnum::HOD->value)
                        ->whereIn('user_id', self::departmentUserIds($user));
                });
            }
        });
    }

    private static function departmentUserIds(User $user): Collection
    {
        return $user->department?->users()->pluck('id') ?? collect();
    }

    private static function directedUserIds(User $user): Collection
    {
        return User::whereIn(
            'department_id',
            Department::where('director_id', $user->id)->pluck('id')
        )->pluck('id');
    }
}
