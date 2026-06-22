<div class="ems-table-wrap">
    <table class="ems-table">
        <thead>
            <tr>
                <th>Nomor Employee</th>
                <th>Nama Lengkap</th>
                <th>Departemen</th>
                <th>Posisi</th>
                <th>Status</th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $employee)
                <tr>
                    <td class="ems-table__name">{{ $employee->employee_number }}</td>
                    <td>{{ $employee->full_name }}</td>
                    <td class="ems-table__muted">{{ $employee->department?->name ?? '-' }}</td>
                    <td>{{ $employee->position?->name ?? '-' }}</td>
                    <td>
                        @if ($employee->employment_status === 'active')
                            <span class="ems-pill ems-pill--success">Aktif</span>
                        @elseif ($employee->employment_status === 'resigned')
                            <span class="ems-pill ems-pill--warning">Resign</span>
                        @else
                            <span class="ems-pill ems-pill--danger">Terminated</span>
                        @endif
                    </td>
                    <td>
                        <div class="ems-table__actions">
                            <button
                                type="button"
                                class="ems-icon-btn ems-icon-btn--edit"
                                title="Edit"
                                @click="openEdit({
                                    employee_id: {{ $employee->employee_id }},
                                    employee_number: @js($employee->employee_number),
                                    full_name: @js($employee->full_name),
                                    gender: @js($employee->gender),
                                    birth_date: @js($employee->birth_date),
                                    phone: @js($employee->phone),
                                    address: @js($employee->address),
                                    join_date: @js($employee->join_date),
                                    employment_status: @js($employee->employment_status),
                                    department_id: {{ $employee->department_id }},
                                    position_id: {{ $employee->position_id }},
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button
                                type="button"
                                class="ems-icon-btn ems-icon-btn--delete"
                                title="Hapus"
                                @click="openDelete({
                                    employee_id: {{ $employee->employee_id }},
                                    full_name: @js($employee->full_name),
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <p>
                                @if (request('search'))
                                    Employee tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada employee
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<x-pagination :paginator="$employees" />
