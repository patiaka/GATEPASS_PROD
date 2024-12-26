<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        // Utilisez la méthode can() pour vérifier l'autorisation en fonction de la politique (Policy) et de l'action
        // if ($this->route()->getName() === 'user.update') {
        //     return $this->user()->can('update', $this->route('user'));
        // }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return flash('validation failed check your information!', 'error');
    }
}
