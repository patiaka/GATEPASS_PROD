<?php
declare(strict_types=1);
namespace App\Models;

use App\Enum\MaterialRequestStatus;
use App\Helper\ModelAction;
use App\Helper\RequestVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class MaterialRequest extends Model
{
    use ModelAction, RequestVisibility;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'user_id',
        'status',
        'gm_approval_id',
        'gm_comment',
        'gm_approval_date',
        'director_approval_id',
        'director_comment',
        'director_approval_date',
        'hod_approval_id',
        'hod_comment',
        'hod_approval_date',
        'expire_at',
        'company',
        'person_out_id',
        'person_out_name',
        'next_approver_role',
    ];

    protected $appends = ['full_name'];

    /**
     * Get the person out that owns the MaterialRequest
     */
    public function person_out(): BelongsTo
    {
        return $this->belongsTo(User::class, 'person_out_id');
    }

    /**
     * Get all of the documents for the MaterialRequest
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get all of the material_request_items for the MaterialRequest
     */
    public function material_request_items(): HasMany
    {
        return $this->hasMany(MaterialRequestItem::class);
    }

    public function getFullNameAttribute(): string
    {
        // person_out peut être null si la personne a été saisie manuellement
        // (person_out_name) ou s'il n'y a pas de personne déléguée.
        $person = $this->person_out?->name ?? $this->person_out_name;

        return $person ? "{$this->reference} — {$person}" : $this->reference;
    }

    public function isExpire(): bool
    {
        return $this->expire_at !== null && $this->expire_at->isPast();
    }

    public function markAsExpiredIfNeeded(): void
    {
        if ($this->isExpire() && $this->status !== MaterialRequestStatus::Expired) {
            $this->update(['status' => MaterialRequestStatus::Expired]);
        }
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => MaterialRequestStatus::class,
            'expire_at' => 'datetime',
        ];
    }
}
