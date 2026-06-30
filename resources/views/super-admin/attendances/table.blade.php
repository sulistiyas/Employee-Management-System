<div class="ems-dt-wrap">
    <table class="ems-dt">
        <thead>
            <tr>
                <th class="ems-dt__checkbox">
                    <input type="checkbox" class="ems-dt__check" @change="toggleAll($event)" :checked="allSelected">
                </th>
                <th>Karyawan</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Terlambat</th>
                <th>Status</th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td class="ems-dt__checkbox">
                        <input
                            type="checkbox"
                            class="ems-dt__check"
                            :value="{{ $attendance->attendance_id }}"
                            x-model="selected"
                            @change="updateCount()"
                        >
                    </td>
                    <td class="ems-table__name">{{ $attendance->employee->full_name }}</td>
                    <td>{{ $attendance->attendance_date->format('d M Y') }}</td>
                    <td>{{ $attendance->check_in ? \Illuminate\Support\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->check_out ? \Illuminate\Support\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->late_minutes > 0 ? $attendance->late_minutes . ' menit' : '-' }}</td>
                    <td>
                        <span class="ems-pill ems-pill--{{ $attendance->attendance_status }}">
                            {{ \App\Models\Attendances::STATUSES[$attendance->attendance_status] ?? $attendance->attendance_status }}
                        </span>
                    </td>
                    <td>
                        <div class="ems-dt-actions">
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--edit"
                                title="Edit"
                                @click="openEdit({
                                    attendance_id: {{ $attendance->attendance_id }},
                                    employee_id: {{ $attendance->employee_id }},
                                    attendance_date: @js($attendance->attendance_date->format('Y-m-d')),
                                    check_in: @js($attendance->check_in ? \Illuminate\Support\Carbon::parse($attendance->check_in)->format('H:i') : ''),
                                    check_out: @js($attendance->check_out ? \Illuminate\Support\Carbon::parse($attendance->check_out)->format('H:i') : ''),
                                    attendance_status: @js($attendance->attendance_status),
                                    notes: @js($attendance->notes),
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--delete"
                                title="Hapus"
                                @click="openDelete({
                                    attendance_id: {{ $attendance->attendance_id }},
                                    name: @js($attendance->employee->full_name . ' - ' . $attendance->attendance_date->format('d M Y')),
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="ems-dt__empty">
                            <div class="ems-dt__empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </div>
                            <p class="ems-dt__empty-text">
                                @if (request('search'))
                                    Data absensi tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada data absensi
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="ems-dt-footer">
    <div class="ems-dt-info">
        Menampilkan <strong>{{ $attendances->firstItem() ?? 0 }}</strong>-<strong>{{ $attendances->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $attendances->total() }}</strong> data absensi
    </div>
    <x-pagination :paginator="$attendances" />
</div>
