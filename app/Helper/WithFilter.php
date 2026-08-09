<?php

declare(strict_types=1);

namespace App\Helper;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

trait WithFilter
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $department = '';

    public string $debut = '';

    public string $fin = '';

    /** Filtre rapide par période : all | today | 24h | week | month */
    #[Url]
    public string $period = 'all';

    public array $selectedRows = [];

    /** Change la période active et revient à la première page. */
    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, ['all', 'today', '24h', 'week', 'month'], true) ? $period : 'all';
        $this->resetPage();
    }

    /** Applique le filtre de période sur la colonne created_at (mutation du builder). */
    protected function applyPeriod(Builder $query): void
    {
        match ($this->period) {
            'today' => $query->whereDate('created_at', Carbon::today()->toDateString()),
            '24h' => $query->where('created_at', '>=', Carbon::now()->subDay()),
            'week' => $query->where('created_at', '>=', Carbon::now()->subWeek()),
            'month' => $query->where('created_at', '>=', Carbon::now()->subMonth()),
            default => null,
        };
    }

    public function selectAll(): void
    {
        $this->selectedRows = $this->rows->pluck('id')->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectedRows = [];
    }

    public function toggleSelectAll(): void
    {
        if (count($this->selectedRows) === $this->rows->count()) {
            $this->deselectAll();
        } else {
            $this->selectAll();
        }
    }
}
