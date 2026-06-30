<div class="ems-dt-wrap">
    <table class="ems-dt">
        <thead>
            <tr>
                <th class="ems-dt__checkbox">
                    <input type="checkbox" class="ems-dt__check" @change="toggleAll($event)" :checked="allSelected">
                </th>
                <th>Nama Jenis Cuti</th>
                <th>Maksimal Hari</th>
                <th>Status</th>
                <th>Jumlah Pengajuan</th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaveTypes as $leaveType)
                <tr>
                    <td class="ems-dt__checkbox">
                        <input
                            type="checkbox"
                            class="ems-dt__check"
                            :value="{{ $leaveType->leave_type_id }}"
                            x-model="selected"
                            @change="updateCount()"
                        >
                    </td>
                    <td class="ems-table__name">{{ $leaveType->name }}</td>
                    <td>{{ $leaveType->max_days }} hari</td>
                    <td>
                        @if ($leaveType->is_paid)
                            <span class="ems-pill ems-pill--success">Berbayar</span>
                        @else
                            <span class="ems-pill ems-pill--warning">Tidak Berbayar</span>
                        @endif
                    </td>
                    <td>{{ $leaveType->leave_requests_count ?? 0 }}</td>
                    <td>
                        <div class="ems-dt-actions">
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--edit"
                                title="Edit"
                                @click="openEdit({
                                    leave_type_id: {{ $leaveType->leave_type_id }},
                                    name: @js($leaveType->name),
                                    max_days: {{ $leaveType->max_days }},
                                    is_paid: {{ $leaveType->is_paid ? 1 : 0 }},
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--delete"
                                title="Hapus"
                                @click="openDelete({
                                    leave_type_id: {{ $leaveType->leave_type_id }},
                                    name: @js($leaveType->name),
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </div>
                            <p class="ems-dt__empty-text">
                                @if (request('search'))
                                    Jenis cuti tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada jenis cuti
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
        Menampilkan <strong>{{ $leaveTypes->firstItem() ?? 0 }}</strong>-<strong>{{ $leaveTypes->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $leaveTypes->total() }}</strong> jenis cuti
    </div>
    <x-pagination :paginator="$leaveTypes" />
</div>