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
            'name'                   => ['required', 'string', 'in:pagi,sore,malam'],
            // 'code'                   => ['required', 'string', 'max:50', Rule::unique('shifts', 'code')->ignore($shiftId, 'shift_id')],
            'start_time'             => ['required', 'date_format:H:i'],
            'end_time'               => ['required', 'date_format:H:i'],
            'late_tolerance_minutes' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                   => 'Jenis shift wajib dipilih.',
            'name.in'                         => 'Jenis shift tidak valid.',
            'start_time.required'             => 'Jam mulai wajib diisi.',
            'start_time.date_format'          => 'Format jam mulai tidak valid (HH:MM).',
            'end_time.required'               => 'Jam selesai wajib diisi.',
            'end_time.date_format'            => 'Format jam selesai tidak valid (HH:MM).',
            'late_tolerance_minutes.required' => 'Toleransi telat wajib diisi.',
            'late_tolerance_minutes.integer'  => 'Toleransi telat harus berupa angka.',
            'late_tolerance_minutes.min'      => 'Toleransi telat tidak boleh negatif.',
        ];
    }
}