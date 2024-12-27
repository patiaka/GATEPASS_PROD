<?php

namespace App\Models;

use App\Helper\DateFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use DateFormat;

    /**
     * Get the material_request that owns the Document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material_request(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
