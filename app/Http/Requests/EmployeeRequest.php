<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_number' => ['required', 'string', 'max:255', 'unique:employees,employee_number'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
            'birth_date' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'join_date' => ['required', 'date'],
            'employment_status' => ['required', 'string', 'in:active,resigned,terminated'],
            'department_id' => ['required', 'integer', 'exists:departments,department_id'],
            'position_id' => ['required', 'integer', 'exists:positions,position_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_number.required' => 'Nomor employee wajib diisi.',
            'employee_number.unique' => 'Nomor employee sudah terdaftar.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'join_date.required' => 'Tanggal bergabung wajib diisi.',
            'employment_status.required' => 'Status pekerjaan wajib dipilih.',
            'department_id.required' => 'Departemen wajib dipilih.',
            'position_id.required' => 'Posisi wajib dipilih.',
        ];
    }
}
