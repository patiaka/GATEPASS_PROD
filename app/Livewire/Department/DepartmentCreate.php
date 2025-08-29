<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Livewire\Forms\DepartmentForm;

#[Title('Department Off Site Form')]
final class DepartmentCreate extends Component
{
    public DepartmentForm $form;

    public function save()
    {
        $this->form->store();
    }
}
