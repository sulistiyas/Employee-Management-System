<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $departmentId = $this->route('department')?->department_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('departments', 'code')->ignore($departmentId, 'department_id')],
            'description' => ['nullable', 'string'],
            'manager_employee_id' => ['nullable', 'integer', 'exists:employees,employee_id'],
            'hr_employee_id' => ['nullable', 'integer', 'exists:employees,employee_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama departemen wajib diisi.',
            'code.required' => 'Kode departemen wajib diisi.',
            'code.unique' => 'Kode sudah digunakan oleh departemen lain.',
            'manager_employee_id.exists' => 'Karyawan yang dipilih sebagai manager tidak valid.',
            'hr_employee_id.exists' => 'Karyawan yang dipilih sebagai HR tidak valid.',
        ];
    }
}