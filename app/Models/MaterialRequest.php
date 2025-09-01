<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\MaterialRequestStatus;
use App\Helper\ModelAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class MaterialRequest extends Model
{
    use ModelAction;

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
        'hod_approval_id',
        'hod_comment',
        'hod_approval_date',
        'expire_at',
        'company',
    ];

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
}
