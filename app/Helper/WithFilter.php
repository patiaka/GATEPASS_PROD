<?php

declare(strict_types=1);

namespace App\Helper;

use Livewire\WithPagination;

trait WithFilter
{
    use WithPagination;

    public string $search = '';

    public string $etat = '';

    public string $date = '';
}
