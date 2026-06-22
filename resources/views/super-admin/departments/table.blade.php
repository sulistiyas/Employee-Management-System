<div class="ems-dt-wrap">
    <table class="ems-dt">
        <thead>
            <tr>
                <th class="ems-dt__checkbox">
                    <input type="checkbox" class="ems-dt__check" @change="toggleAll($event)" :checked="allSelected">
                </th>
                <th>Nama Departemen</th>
                <th>Kode</th>
                <th>Deskripsi</th>
                <th>Jumlah Employees</th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($departments as $department)
                <tr>
                    <td class="ems-dt__checkbox">
                        <input
                            type="checkbox"
                            class="ems-dt__check"
                            :value="{{ $department->department_id }}"
                            x-model="selected"
                            @change="updateCount()"
                        >
                    </td>
                    <td class="ems-table__name">{{ $department->name }}</td>
                    <td>
                        <span class="ems-pill ems-pill--leave">{{ $department->code }}</span>
                    </td>
                    <td class="ems-table__muted">{{ $department->description ?: '-' }}</td>
                    <td>{{ $department->employees_count ?? 0 }}</td>
                    <td>
                        <div class="ems-dt-actions">
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--edit"
                                title="Edit"
                                @click="openEdit({
                                    department_id: {{ $department->department_id }},
                                    name: @js($department->name),
                                    code: @js($department->code),
                                    description: @js($department->description),
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--delete"
                                title="Hapus"
                                @click="openDelete({
                                    department_id: {{ $department->department_id }},
                                    name: @js($department->name),
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
                        <div class="ems-dt__empty">
                            <div class="ems-dt__empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <p class="ems-dt__empty-text">
                                @if (request('search'))
                                    Departemen tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada departemen
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
        Menampilkan <strong>{{ $departments->firstItem() ?? 0 }}</strong>-<strong>{{ $departments->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $departments->total() }}</strong> departemen
    </div>
    <x-pagination :paginator="$departments" />
</div>
