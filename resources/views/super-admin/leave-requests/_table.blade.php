<div class="ems-table-wrap">
    <table class="ems-table">
        <thead>
            <tr>
                <th>Karyawan</th>
                <th>Departemen</th>
                <th>Jenis Cuti</th>
                <th>Periode</th>
                <th>Total Hari</th>
                <th>Alasan</th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaveRequests as $leaveRequest)
                <tr>
                    <td class="ems-table__name">{{ $leaveRequest->employee->full_name }}</td>
                    <td class="ems-table__muted">{{ $leaveRequest->employee->department->name ?? '-' }}</td>
                    <td>{{ $leaveRequest->leaveType->name ?? '-' }}</td>
                    <td>{{ $leaveRequest->start_date->format('d M Y') }} - {{ $leaveRequest->end_date->format('d M Y') }}</td>
                    <td>{{ $leaveRequest->total_days }} hari</td>
                    <td class="ems-table__muted">{{ \Illuminate\Support\Str::limit($leaveRequest->reason, 40) }}</td>
                    <td>
                        <div class="ems-table__actions">
                            <form method="POST" action="{{ url($rolePrefix . '/leave-requests/' . $leaveRequest->leave_request_id . '/approve') }}">
                                @csrf
                                <button type="submit" class="ems-btn ems-btn--primary ems-btn--sm" title="Setujui">
                                    Setujui
                                </button>
                            </form>

                            <button
                                type="button"
                                class="ems-btn ems-btn--ghost ems-btn--sm"
                                title="Tolak"
                                @click="openReject({
                                    leave_request_id: {{ $leaveRequest->leave_request_id }},
                                    employee_name: @js($leaveRequest->employee->full_name),
                                })"
                            >
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="ems-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <p>
                                @if (request('search'))
                                    Pengajuan cuti tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Tidak ada pengajuan cuti yang menunggu persetujuan
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<x-pagination :paginator="$leaveRequests" />