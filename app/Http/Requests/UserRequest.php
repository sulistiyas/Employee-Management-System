<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId, 'id')],
            'password'    => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8'],
            'role_id'     => ['required', 'integer', 'exists:roles,role_id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,employee_id'],
            'is_active'   => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama user wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'role_id.required'  => 'Role wajib dipilih.',
            'role_id.exists'    => 'Role yang dipilih tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => (bool) $this->is_active,
        ]);
    }
}