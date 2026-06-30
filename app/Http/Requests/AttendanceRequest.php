<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attendanceId = $this->route('attendance')?->attendance_id;

        return [
            'employee_id' => ['required', 'exists:employees,employee_id'],
            'attendance_date' => [
                'required',
                'date',
                Rule::unique('attendances', 'attendance_date')
                    ->where('employee_id', $this->input('employee_id'))
                    ->ignore($attendanceId, 'attendance_id'),
            ],
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i', 'after:check_in'],
            'attendance_status' => ['nullable', 'string', Rule::in(['present', 'late', 'absent', 'permit'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Karyawan wajib dipilih.',
            'employee_id.exists' => 'Karyawan tidak ditemukan.',
            'attendance_date.required' => 'Tanggal absensi wajib diisi.',
            'attendance_date.date' => 'Format tanggal tidak valid.',
            'attendance_date.unique' => 'Karyawan ini sudah memiliki data absensi pada tanggal tersebut.',
            'check_in.date_format' => 'Format jam masuk tidak valid (HH:MM).',
            'check_out.date_format' => 'Format jam keluar tidak valid (HH:MM).',
            'check_out.after' => 'Jam keluar harus setelah jam masuk.',
            'attendance_status.in' => 'Status absensi tidak valid.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}
