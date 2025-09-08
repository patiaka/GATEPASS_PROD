<?php

declare(strict_types=1);

namespace App\Helper;

use App\Models\User;
use App\Models\Recording;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enum\MaterialRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

trait ModelAction
{
    use HasFactory;

    public function recordings(): MorphMany
    {
        return $this->morphMany(Recording::class, 'requestable');
    }
    #[Scope]
    public function scopeForUser(Builder $query): Builder
    {
        $auth = Auth::user();
        return $auth && $auth->isUser()
            ? $query->where('user_id', $auth->id)
            : $query;
    }

    public function getGmApprovalDateFormatAttribute(): string
    {
        return Carbon::parse($this->gm_approval_date)->format('d/m/Y H:i');
    }

    public function getHodApprovalDateFormatAttribute(): string
    {
        return Carbon::parse($this->hod_approval_date)->format('d/m/Y H:i');
    }

    public function isApproved(): bool
    {
        return $this->status === MaterialRequestStatus::Approved;
    }

    public function isRejected(): bool
    {
        return $this->status === MaterialRequestStatus::Rejected;
    }

    public function isPending(): bool
    {
        return $this->status === MaterialRequestStatus::Pending;
    }

    public function isProgress(): bool
    {
        return $this->status === MaterialRequestStatus::Progress;
    }

    public function isExpired(): bool
    {
        return $this->status === MaterialRequestStatus::Expired;
    }

    public function gm_approval_view(): string
    {
        return $this->gmApproval ? $this->gmApproval->name : 'waiting';
    }

    public function hod_approval_view(): string
    {
        return $this->hodApproval ? $this->hodApproval->name : 'waiting';
    }

    public function getStatusFor(string $actor): array
    {
        $status = ['⏳ Pending', 'btn-secondary'];

        if ($actor === 'hod') {
            if ($this->isHodApproved()) {
                $status = ['✅ Approved', 'btn-success'];
            }
            if ($this->isHodApproved() && $this->isRejected()) {
                $status = ['❌ Rejected', 'btn-danger'];
            }
        }

        if ($actor === 'gm') {
            if ($this->isApproved() && $this->isGmApproved()) {
                $status = ['✅ Approved', 'btn-success'];
            }
            if ($this->isRejected() && $this->isGmApproved()) {
                $status = ['❌ Rejected', 'btn-danger'];
            }
            if ($this->isProgress()) {
                $status = ['⏳ Pending', 'btn-secondary'];
            }
        }

        return $status;
    }

    // Vérifier si GM a validé
    public function isGmApproved()
    {
        return ! is_null($this->gmApproval);
    }

    // Vérifier si HOD a validé
    public function isHodApproved()
    {
        return ! is_null($this->hodApproval);
    }

    /**
     * Get the user that owns the MaterialRequest
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hod_approval that owns the MaterialRequest
     */
    public function hodApproval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hod_approval_id');
    }

    /**
     * Get the gm_approval that owns the MaterialRequest
     */
    public function gmApproval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gm_approval_id');
    }

    public function generateId(string $prefix_type)
    {
        $currentYear = Carbon::today()->format('Y');
        $prefix = $prefix_type . $currentYear . '-';

        return DB::transaction(function () use ($prefix) {
            // Verrouille le dernier identifiant de courrier enregistré dans la base de données pour la mise à jour
            $lastCourrier = self::where('reference', 'like', $prefix . '%')->whereNotNull('reference')
                ->latest('id')
                ->lockForUpdate()
                ->first(['reference']);
            // Si aucun identifiant de courrier n'a été enregistré, définit le numéro de séquence à 0
            $sequence = 0;
            if ($lastCourrier) {
                // Récupère le numéro de séquence de l'identifiant de courrier précédent
                $sequence = (int) mb_substr($lastCourrier->reference, mb_strlen($prefix));
            }
            // Incrémente le numéro de séquence et génère le nouvel identifiant de courrier
            $sequence++;
            $newCourrierNumber = $prefix . $sequence;
            // Met à jour le numéro de courrier de l'instance courante
            $this->reference = $newCourrierNumber;
            $this->save();

            return $this;
        });
    }

    protected function getCreatedAtAttribute(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }
}
