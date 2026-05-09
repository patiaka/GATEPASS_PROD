<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Department;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class DepartmentForm extends Form
{
    public ?Department $department;

    #[Validate('required|string|unique:departments,name')]
    public string $name = '';

    #[Validate('nullable|exists:users,id')]
    public string|null $director_id = '';

    public function setDepartment(Department $department): void
    {
        $this->department = $department;

        $this->name = $department->name;
        $this->director_id = $department->director_id;
    }

    public function store(): void
    {
        $validated = $this->validate();
        $validated['director_id'] = $validated['director_id'] === '' ? null : $validated['director_id'];

        Department::create($validated);

        $this->reset();
        flash()->success('Department added successfully');
    }

    public function update(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', Rule::unique('departments', 'name')->ignore($this->department->id)],
            'director_id' => ['nullable', 'exists:users,id'],
        ]);

        $validated['director_id'] = $validated['director_id'] === '' ? null : $validated['director_id'];

        $this->department->update($validated);
        flash()->success('Department updated successfully');
    }
}
