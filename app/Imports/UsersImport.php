<?php

namespace App\Imports;

use App\Models\User;
use App\Enum\RoleEnum;
use App\Models\Compagnie;
use App\Models\Department;
use Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    public $errors = []; // Stockage des erreurs
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            dd($row['role']);
            $role = RoleEnum::getValue($row['role']);
            $department = Department::where('name', $row['department'])->firstOrFail();
            $compagnie = Compagnie::where('name', $row['compagny'])->firstOrFail();
            return new User([
                'name'  => $row['name'],
                'email' => $row['email'],
                'poste'    => $row['position'],
                'role'    => $role,
                'department_id' => $department->id,
                'compagnie_id' => $compagnie->id,
            ]);
        } catch (\Exception $e) {
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
            'role' => 'required|in:' . implode(',', RoleEnum::cases()),
            'department' => 'required|exists:departments,name',
            'compagny' => 'required|exists:compagnies,name',
        ];
    }

    // Capture les erreurs
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = [
                'ligne' => $failure['row'],
                'attribut' => $failure['attribute'],
                'erreur' => $failure['errors']
            ];
        }
    }
}
