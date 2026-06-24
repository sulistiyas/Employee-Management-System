<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeShiftAssignRequest extends FormRequest
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
            'effective_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_ids.required' => 'Pilih minimal satu karyawan untuk di-assign.',
            'employee_ids.min' => 'Pilih minimal satu karyawan untuk di-assign.',
            'employee_ids.*.exists' => 'Salah satu karyawan yang dipilih tidak valid.',
            'effective_date.required' => 'Tanggal efektif wajib diisi.',
            'effective_date.after_or_equal' => 'Tanggal efektif tidak boleh di masa lalu.',
        ];
    }
}
