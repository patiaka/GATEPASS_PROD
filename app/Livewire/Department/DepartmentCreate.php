<?php

namespace App\Livewire\Department;

use Livewire\Component;
use App\Livewire\Forms\DepartmentForm;

class DepartmentCreate extends Component
{
    public DepartmentForm $form;

    public function save()
    {
        $this->form->store();
    }
}
