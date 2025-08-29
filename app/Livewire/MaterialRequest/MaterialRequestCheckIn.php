<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Check material request')]
final class MaterialRequestCheckIn extends Component
{
    public function render()
    {
        return view('livewire.material-request.material-request-check-in');
    }
}
