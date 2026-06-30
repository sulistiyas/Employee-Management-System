<div class="ems-dt-wrap">
    <table class="ems-dt">
        <thead>
            <tr>
                <th class="ems-dt__checkbox">
                    <input type="checkbox" class="ems-dt__check" @change="toggleAll($event)" :checked="allSelected">
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('employee_number')">
                        No. Karyawan
                        <span class="ems-dt__sort-icon" :class="getSortClass('employee_number')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('full_name')">
                        Nama
                        <span class="ems-dt__sort-icon" :class="getSortClass('full_name')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>Departemen</th>
                <th>Posisi</th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('effective_date')">
                        Berlaku Sejak
                        <span class="ems-dt__sort-icon" :class="getSortClass('effective_date')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assignments as $assignment)
                <tr>
                    <td class="ems-dt__checkbox">
                        <input
                            type="checkbox"
                            class="ems-dt__check"
                            :value="{{ $assignment->employee_id }}"
                            x-model="selected"
                            @change="updateCount()"
                        >
                    </td>
                    <td>{{ $assignment->employee->employee_number }}</td>
                    <td class="ems-table__name">{{ $assignment->employee->full_name }}</td>
                    <td>{{ $assignment->employee->department->name ?? '-' }}</td>
                    <td>{{ $assignment->employee->position->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($assignment->effective_date)->format('d M Y') }}</td>
                    <td>
                        <div class="ems-dt-actions">
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--delete"
                                title="Copot dari shift ini"
                                @click="openRemoveSingle({
                                    employee_id: {{ $assignment->employee_id }},
                                    full_name: @js($assignment->employee->full_name),
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <p class="ems-dt__empty-text">
                                @if (request('search'))
                                    Karyawan tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada karyawan yang di-assign ke shift ini
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
        Menampilkan <strong>{{ $assignments->firstItem() ?? 0 }}</strong>-<strong>{{ $assignments->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $assignments->total() }}</strong> karyawan
    </div>
    <x-pagination :paginator="$assignments" />
</div>