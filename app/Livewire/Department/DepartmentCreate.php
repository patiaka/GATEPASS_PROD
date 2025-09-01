<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Livewire\Forms\DepartmentForm;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Department Off Site Form')]
final class DepartmentCreate extends Component
{
    public DepartmentForm $form;

    public function save()
    {
        $this->form->store();
    }
}
