<?php

namespace App\Models;

use App\Helper\DateFormat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enum\MaterialRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRequest extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialRequestFactory> */
    use HasFactory;

    protected function getCreatedAtAttribute(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }

    public function getGmApprovalDateFormatAttribute(): string
    {
        return Carbon::parse($this->gm_approval_date)->format('d/m/Y H:i');
    }

    public function getHodApprovalDateFormatAttribute(): string
    {
        return Carbon::parse($this->hod_approval_date)->format('d/m/Y H:i');
    }

    public function gm_approval_view(): string
    {
        return $this->gmApproval ? $this->user->name : 'no exist';
    }

    public function hod_approval_view(): string
    {
        return $this->hodApproval ? $this->user->name : 'no exist';
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'gm_approval_id',
        'gm_comment',
        'gm_approval_date',
        'hod_approval_id',
        'hod_comment',
        'hod_approval_date',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => MaterialRequestStatus::class,
        ];
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

    /**
     * Get the user that owns the MaterialRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hod_approval that owns the MaterialRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hodApproval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hod_approval_id');
    }

    /**
     * Get the gm_approval that owns the MaterialRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gmApproval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gm_approval_id');
    }

    // Vérifier si GM a validé
    public function isGmApproved()
    {
        return !is_null($this->gm_approval_date);
    }

    // Vérifier si HOD a validé
    public function isHodApproved()
    {
        return !is_null($this->hod_approval_date);
    }

    /**
     * Get all of the documents for the MaterialRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get all of the material_request_items for the MaterialRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function material_request_items(): HasMany
    {
        return $this->hasMany(MaterialRequestItem::class);
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
                $sequence = (int) substr($lastCourrier->reference, strlen($prefix));
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
}
