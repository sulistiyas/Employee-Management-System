<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeShiftRemoveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_ids' => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['integer', 'exists:employees,employee_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_ids.required' => 'Pilih minimal satu karyawan untuk dicopot.',
            'employee_ids.min' => 'Pilih minimal satu karyawan untuk dicopot.',
            'employee_ids.*.exists' => 'Salah satu karyawan yang dipilih tidak valid.',
        ];
    }
}
