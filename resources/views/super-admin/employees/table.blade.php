<div class="ems-dt-wrap">
    <table class="ems-dt">
        <thead>
            <tr>
                <th class="ems-dt__checkbox">
                    <input type="checkbox" class="ems-dt__check" @change="toggleAll($event)" :checked="allSelected">
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('employee_number')">
                        Nomor Employee
                        <span class="ems-dt__sort-icon" :class="getSortClass('employee_number')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('full_name')">
                        Nama Lengkap
                        <span class="ems-dt__sort-icon" :class="getSortClass('full_name')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('department')">
                        Departemen
                        <span class="ems-dt__sort-icon" :class="getSortClass('department')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('position')">
                        Posisi
                        <span class="ems-dt__sort-icon" :class="getSortClass('position')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('employment_status')">
                        Status
                        <span class="ems-dt__sort-icon" :class="getSortClass('employment_status')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $employee)
                <tr>
                    <td class="ems-dt__checkbox">
                        <input
                            type="checkbox"
                            class="ems-dt__check"
                            :value="{{ $employee->employee_id }}"
                            x-model="selected"
                            @change="updateCount()"
                        >
                    </td>
                    <td class="ems-table__name">{{ $employee->employee_number }}</td>
                    <td>{{ $employee->full_name }}</td>
                    <td class="ems-table__muted">{{ $employee->department?->name ?? '-' }}</td>
                    <td>{{ $employee->position?->name ?? '-' }}</td>
                    <td>
                        @if ($employee->employment_status === 'Permanent')
                            <span class="ems-pill ems-pill--success">Permanent</span>
                        @elseif ($employee->employment_status === 'Contract')
                            <span class="ems-pill ems-pill--warning">Contract</span>
                        @endif
                    </td>
                    <td>
                        <div class="ems-dt-actions">
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--edit"
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
                                class="ems-dt-action ems-dt-action--delete"
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
                    <td colspan="7">
                        <div class="ems-dt__empty">
                            <div class="ems-dt__empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <p class="ems-dt__empty-text">
                                @if (request('search'))
                                    Employee tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada employee
                                @endif
                            </p>
                            @if (request('department') || request('position') || request('status'))
                                <p class="ems-dt__empty-sub">Coba reset filter untuk melihat data lainnya.</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="ems-dt-footer">
    <div class="ems-dt-info">
        Menampilkan <strong>{{ $employees->firstItem() ?? 0 }}</strong>-<strong>{{ $employees->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $employees->total() }}</strong> employee
    </div>
    <x-pagination :paginator="$employees" />
</div>