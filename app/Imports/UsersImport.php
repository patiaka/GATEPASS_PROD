<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enum\RoleEnum;
use App\Models\Department;
use App\Models\User;
use Exception;
use Log;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;

final class UsersImport implements SkipsOnFailure, ToModel, WithHeadingRow
{
    public $errors = []; // Stockage des erreurs

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            dd($row['role']);
            $role = RoleEnum::getValue($row['role']);
            $department = Department::where('name', $row['department'])->firstOrFail();

            return new User([
                'name' => $row['name'],
                'email' => $row['email'],
                'poste' => $row['position'],
                'role' => $role,
                'department_id' => $department->id,
                'compagnie' => $row['compagny'],
            ]);
        } catch (Exception $e) {
            Log::alert($e);
        }
    }

    // Ajoute les règles de validation
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email'],
            'position' => 'required|string|max:255',
            'role' => 'required|in:'.implode(',', RoleEnum::cases()),
            'department' => 'required|exists:departments,name',
            'compagny' => 'required|string',
        ];
    }

    // Capture les erreurs
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = [
                'ligne' => $failure['row'],
                'attribut' => $failure['attribute'],
                'erreur' => $failure['errors'],
            ];
        }
    }
}
