<div class="ems-dt-wrap">
    <table class="ems-dt">
        <thead>
            <tr>
                <th class="ems-dt__checkbox">
                    <input type="checkbox" class="ems-dt__check" @change="toggleAll($event)" :checked="allSelected">
                </th>
                <th>Karyawan</th>
                <th>Jenis Cuti</th>
                <th>Periode</th>
                <th>Total Hari</th>
                <th>Status</th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaveRequests as $leaveRequest)
                <tr>
                    <td class="ems-dt__checkbox">
                        <input
                            type="checkbox"
                            class="ems-dt__check"
                            :value="{{ $leaveRequest->leave_request_id }}"
                            x-model="selected"
                            @change="updateCount()"
                        >
                    </td>
                    <td class="ems-table__name">{{ $leaveRequest->employee->full_name }}</td>
                    <td>{{ $leaveRequest->leaveType->name }}</td>
                    <td>{{ $leaveRequest->start_date->format('d M Y') }} - {{ $leaveRequest->end_date->format('d M Y') }}</td>
                    <td>{{ $leaveRequest->total_days }} hari</td>
                    <td>
                        @if ($leaveRequest->status === 'approved')
                            <span class="ems-badge ems-badge--active">Disetujui</span>
                        @elseif ($leaveRequest->status === 'rejected')
                            <span class="ems-badge ems-badge--danger">Ditolak</span>
                        @else
                            <span class="ems-badge ems-badge--pending">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        <div class="ems-dt-actions">
                            @if ($leaveRequest->status === 'pending')
                                <form method="POST" action="{{ route('super-admin.leave-requests.approve', $leaveRequest) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="ems-dt-action ems-dt-action--edit" title="Setujui">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('super-admin.leave-requests.reject', $leaveRequest) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="ems-dt-action ems-dt-action--delete" title="Tolak">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </form>
                            @endif
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--edit"
                                title="Edit"
                                @click="openEdit({
                                    leave_request_id: {{ $leaveRequest->leave_request_id }},
                                    employee_id: {{ $leaveRequest->employee_id }},
                                    leave_type_id: {{ $leaveRequest->leave_type_id }},
                                    start_date: @js($leaveRequest->start_date->format('Y-m-d')),
                                    end_date: @js($leaveRequest->end_date->format('Y-m-d')),
                                    reason: @js($leaveRequest->reason),
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--delete"
                                title="Hapus"
                                @click="openDelete({
                                    leave_request_id: {{ $leaveRequest->leave_request_id }},
                                    name: @js($leaveRequest->employee->full_name),
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </div>
                            <p class="ems-dt__empty-text">
                                @if (request('search'))
                                    Pengajuan cuti tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada pengajuan cuti
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
        Menampilkan <strong>{{ $leaveRequests->firstItem() ?? 0 }}</strong>-<strong>{{ $leaveRequests->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $leaveRequests->total() }}</strong> pengajuan cuti
    </div>
    <x-pagination :paginator="$leaveRequests" />
</div>
