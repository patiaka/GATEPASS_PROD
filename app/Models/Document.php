<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

final class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['material_request_id', 'chemin'];

    /**
     * Get the material_request that owns the Document
     */
    public function material_request(): BelongsTo
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function DocLink(): string
    {
        return Storage::url($this->chemin);
    }
}
