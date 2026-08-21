<?php

declare(strict_types=1);

namespace App\Imports;

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

    /**
     * Le département et le rôle sont choisis dans l'interface et appliqués à
     * toutes les lignes importées — le fichier ne contient plus ces colonnes,
     * ce qui évite les fautes de frappe sur les noms de département.
     */
    public function __construct(
        private int $departmentId,
        private string $role,
    ) {}

    public function onRow(Row $row): void
    {
        $data = $row->toArray();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'poste' => $data['position'],
            'contact' => (string) $data['contact'],
            'badge_number' => (string) $data['badge_number'],
            'role' => $this->role,
            'department_id' => $this->departmentId,
            // Mot de passe par défaut : l'utilisateur devra le changer à la connexion
            'password' => Hash::make(User::DEFAULT_PASSWORD),
            'change_password' => false,
        ]);

        // Multi-rôles : alimenter le pivot role_user
        $user->syncRoles([$this->role]);

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
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->errors[] = 'Ligne '.$failure->row().' — '.$failure->attribute().' : '.implode(', ', $failure->errors());
        }
    }
}
