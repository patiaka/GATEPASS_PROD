<?php

declare(strict_types=1);

namespace App\Helper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait DateFormat
{
    use HasFactory;

    protected function getCreatedAtAttribute(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function getDelaiFormatAttribute(): string
    {
        return Carbon::parse($this->delai)->format('d/m/Y');
    }

    public function gm_approval_view(): string
    {
        return $this->gm_approval ? $this->user->email : 'no exist';
    }

    public function hod_approval_view(): string
    {
        return $this->hod_approval ? $this->user->email : 'no exist';
    }
}
