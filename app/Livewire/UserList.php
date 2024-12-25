<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UserList extends Component
{
    #[Computed]
    public function rows()
    {
        return User::paginate(10);
    }
    public function render()
    {
        return view('livewire.user-list');
    }
}
