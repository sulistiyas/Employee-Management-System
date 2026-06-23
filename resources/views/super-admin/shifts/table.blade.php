<div class="ems-table-wrap">
    <table class="ems-table">
        <thead>
            <tr>
                <th>Nama Shift</th>
                <th>Kode</th>
                <th>Jam Kerja</th>
                <th>Toleransi Telat</th>
                <th>Jumlah Karyawan</th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shifts as $shift)
                <tr>
                    <td class="ems-table__name">{{ $shift->name }}</td>
                    <td>
                        <span class="ems-pill ems-pill--leave">{{ $shift->code }}</span>
                    </td>
                    <td class="ems-table__muted">
                        {{ \Illuminate\Support\Carbon::parse($shift->start_time)->format('H:i') }}
                        &ndash;
                        {{ \Illuminate\Support\Carbon::parse($shift->end_time)->format('H:i') }}
                    </td>
                    <td class="ems-table__muted">{{ $shift->late_tolerance_minutes }} menit</td>
                    <td>{{ $shift->employee_shifts_count ?? 0 }}</td>
                    <td>
                        <div class="ems-table__actions">
                            <button
                                type="button"
                                class="ems-icon-btn ems-icon-btn--edit"
                                title="Edit"
                                @click="openEdit({
                                    shift_id: {{ $shift->shift_id }},
                                    name: @js($shift->name),
                                    code: @js($shift->code),
                                    start_time: @js(\Illuminate\Support\Carbon::parse($shift->start_time)->format('H:i')),
                                    end_time: @js(\Illuminate\Support\Carbon::parse($shift->end_time)->format('H:i')),
                                    late_tolerance_minutes: {{ $shift->late_tolerance_minutes }},
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button
                                type="button"
                                class="ems-icon-btn ems-icon-btn--delete"
                                title="Hapus"
                                @click="openDelete({
                                    shift_id: {{ $shift->shift_id }},
                                    name: @js($shift->name),
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="ems-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <p>
                                @if (request('search'))
                                    Shift tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada shift
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<x-pagination :paginator="$shifts" />
