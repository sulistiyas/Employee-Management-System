<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'integer', 'exists:departments,department_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama posisi wajib diisi.',
            'level.required' => 'Level posisi wajib diisi.',
            'department_id.required' => 'Departemen wajib dipilih.',
            'department_id.exists' => 'Departemen yang dipilih tidak valid.',
        ];
    }
}
