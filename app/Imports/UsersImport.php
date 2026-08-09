<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enum\RoleEnum;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Validators\Failure;

final class UsersImport implements OnEachRow, SkipsOnFailure, WithHeadingRow, WithValidation
{
    /** @var array<int, string> Messages d'erreur des lignes rejetées */
    public array $errors = [];

    public int $imported = 0;

    public function onRow(Row $row): void
    {
        $data = $row->toArray();

        $department = Department::where('name', $data['department'])->first();
        if (! $department) {
            return; // sécurité (déjà couvert par la validation exists)
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'poste' => $data['position'],
            'contact' => (string) $data['contact'],
            'badge_number' => (string) $data['badge_number'],
            'role' => $data['role'],
            'department_id' => $department->id,
            // Mot de passe temporaire : l'utilisateur devra le définir à la connexion
            'password' => Hash::make('password'),
            'change_password' => false,
        ]);

        // Multi-rôles : alimenter le pivot role_user
        $user->syncRoles([$data['role']]);

        $this->imported++;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'position' => 'required|string|max:255',
            'contact' => 'required|max:255|unique:users,contact',
            'badge_number' => 'required|max:255|unique:users,badge_number',
            'role' => 'required|in:'.implode(',', array_map(fn (RoleEnum $r) => $r->value, RoleEnum::cases())),
            'department' => 'required|exists:departments,name',
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->errors[] = 'Ligne '.$failure->row().' — '.$failure->attribute().' : '.implode(', ', $failure->errors());
        }
    }
}
