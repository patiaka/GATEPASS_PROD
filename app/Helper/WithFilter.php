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

    /** Change la période active (raccourci) et efface la plage personnalisée. */
    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, ['all', 'today', '24h', 'week', 'month'], true) ? $period : 'all';
        $this->reset('debut', 'fin');
        $this->resetPage();
    }

    /** Une date personnalisée désactive les raccourcis (aucun bouton période actif). */
    public function updatedDebut(): void
    {
        $this->period = 'custom';
        $this->resetPage();
    }

    public function updatedFin(): void
    {
        $this->period = 'custom';
        $this->resetPage();
    }

    /** Applique le filtre de période sur created_at (plage personnalisée prioritaire). */
    protected function applyPeriod(Builder $query): void
    {
        if ($this->debut !== '' || $this->fin !== '') {
            if ($this->debut !== '') {
                $query->whereDate('created_at', '>=', $this->debut);
            }
            if ($this->fin !== '') {
                $query->whereDate('created_at', '<=', $this->fin);
            }

            return;
        }

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
