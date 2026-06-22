<div class="ems-table-wrap">
    <table class="ems-table">
        <thead>
            <tr>
                <th>Nama Posisi</th>
                <th>Level</th>
                <th>Departemen</th>
                <th>Jumlah Employees</th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($positions as $position)
                <tr>
                    <td class="ems-table__name">{{ $position->name }}</td>
                    <td>
                        <span class="ems-pill ems-pill--leave">{{ $position->level }}</span>
                    </td>
                    <td class="ems-table__muted">{{ $position->department?->name ?? '-' }}</td>
                    <td>{{ $position->employees_count ?? 0 }}</td>
                    <td>
                        <div class="ems-table__actions">
                            <button
                                type="button"
                                class="ems-icon-btn ems-icon-btn--edit"
                                title="Edit"
                                @click="openEdit({
                                    position_id: {{ $position->position_id }},
                                    name: @js($position->name),
                                    level: @js($position->level),
                                    department_id: {{ $position->department_id }},
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button
                                type="button"
                                class="ems-icon-btn ems-icon-btn--delete"
                                title="Hapus"
                                @click="openDelete({
                                    position_id: {{ $position->position_id }},
                                    name: @js($position->name),
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="ems-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <p>
                                @if (request('search'))
                                    Posisi tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada posisi
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<x-pagination :paginator="$positions" />
