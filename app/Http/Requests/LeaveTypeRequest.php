<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $leaveTypeId = $this->route('leave_type')?->leave_type_id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('leave_types', 'name')->ignore($leaveTypeId, 'leave_type_id')],
            'max_days' => ['required', 'integer', 'min:0', 'max:365'],
            'is_paid' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama jenis cuti wajib diisi.',
            'name.unique' => 'Nama jenis cuti sudah digunakan.',
            'max_days.required' => 'Maksimal hari cuti wajib diisi.',
            'max_days.integer' => 'Maksimal hari cuti harus berupa angka.',
            'max_days.min' => 'Maksimal hari cuti tidak boleh kurang dari 0.',
            'is_paid.required' => 'Status berbayar wajib dipilih.',
        ];
    }
}