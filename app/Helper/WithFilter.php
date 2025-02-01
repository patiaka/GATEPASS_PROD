<?php

declare(strict_types=1);

namespace App\Helper;

use Livewire\WithPagination;

trait WithFilter
{
    use WithPagination;

    public string $search = "";
    public string $status = "";
    public string $department = "";
    public string $compagny = "";
    public array $selectedRows = [];

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
