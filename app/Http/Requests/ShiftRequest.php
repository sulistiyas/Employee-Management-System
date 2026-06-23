<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $shiftId = $this->route('shift')?->shift_id;

        return [
            'code' => ['required', 'string', 'max:255', Rule::unique('shifts', 'code')->ignore($shiftId, 'shift_id')],
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'late_tolerance_minutes' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode shift wajib diisi.',
            'code.unique' => 'Kode sudah digunakan oleh shift lain.',
            'name.required' => 'Nama shift wajib diisi.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'start_time.date_format' => 'Format jam mulai tidak valid.',
            'end_time.required' => 'Jam selesai wajib diisi.',
            'end_time.date_format' => 'Format jam selesai tidak valid.',
            'late_tolerance_minutes.integer' => 'Toleransi telat harus berupa angka.',
            'late_tolerance_minutes.min' => 'Toleransi telat tidak boleh negatif.',
        ];
    }
}
