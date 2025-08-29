<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Dashboard')]
final class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.dashboard');
    }
}
