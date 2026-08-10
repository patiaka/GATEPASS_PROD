<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Settings')]
final class SettingsIndex extends Component
{
    #[Validate('required|integer|min:1|max:365')]
    public int $material_validity_days = 7;

    public function mount(): void
    {
        $this->material_validity_days = (int) Setting::get('material_validity_days', 7);
    }

    public function save(): void
    {
        $this->validate();

        Setting::put('material_validity_days', $this->material_validity_days);

        flash()->success('Settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.settings.settings-index');
    }
}
