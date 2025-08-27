<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MaterialRequestItem extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialRequestItemFactory> */
    use DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['material_request_id', 'quantity', 'designation', 'serial_number'];

    /**
     * Get the material_request that owns the MaterialRequestItem
     */
    public function material_request(): BelongsTo
    {
        return $this->belongsTo(MaterialRequest::class);
    }
}
