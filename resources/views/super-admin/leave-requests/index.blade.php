@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')

    <div x-data="leaveRequestManager()">

        {{-- Page header --}}
        <div class="ems-page-header">
            <div>
                <h1 class="ems-page-title">Leave Requests</h1>
                <p class="ems-page-subtitle">Kelola pengajuan cuti karyawan.</p>
            </div>
            <div class="ems-page-header__actions">
                <button type="button" class="ems-btn ems-btn--primary" @click="openCreate()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Pengajuan Cuti
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="ems-card ems-card--flush" @click="handlePaginationClick($event)">

            {{-- Toolbar: search + filter status --}}
            <div class="ems-dt-toolbar">
                <div class="ems-dt-toolbar__left">
                    <div class="ems-dt-search">
                        <span class="ems-dt-search__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </span>
                        <input
                            type="text"
                            class="ems-dt-search__input"
                            placeholder="Cari nama karyawan..."
                            x-model="searchQuery"
                            @input.debounce.400ms="handleSearch()"
                        >
                    </div>
                    <span x-show="isLoadingTable" x-cloak class="ems-search-spinner">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="ems-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                    </span>
                    <button
                        type="button"
                        x-show="!isLoadingTable && searchQuery"
                        x-cloak
                        @click="clearSearch()"
                        class="ems-search-clear"
                        title="Hapus pencarian"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>

                    <select class="ems-dt-perpage__select" x-model="statusFilter" @change="handleSearch()">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="ems-dt-toolbar__right">
                    <div class="ems-dt-perpage">
                        <label for="leave_request_per_page">Tampilkan</label>
                        <select id="leave_request_per_page" class="ems-dt-perpage__select" x-model="perPage" @change="changePerPage()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Bulk action bar --}}
            <div class="ems-dt-bulk" x-show="selectedCount > 0" x-cloak>
                <span class="ems-dt-bulk__count"><span x-text="selectedCount"></span> pengajuan terpilih</span>
                <button type="button" class="ems-dt-bulk__btn ems-dt-bulk__btn--danger" @click="deleteSelected()">
                    Hapus Terpilih
                </button>
            </div>

            <form id="bulk-delete-form" method="POST" action="{{ route('super-admin.leave-requests.bulk-destroy') }}">
                @csrf
                @method('DELETE')
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="leave_request_ids[]" :value="id">
                </template>
            </form>

            <div class="ems-card__body">
                <div id="leaveRequestTableContainer" :class="{ 'ems-table-loading': isLoadingTable }">
                    @include('super-admin.leave-requests.table', ['leaveRequests' => $leaveRequests])
                </div>
            </div>
        </div>

        {{-- Modal: Create / Edit --}}
        <div class="ems-modal-overlay" x-show="showFormModal" x-cloak @keydown.escape.window="closeFormModal()">
            <div class="ems-modal" @click.outside="closeFormModal()">
                <form
                    method="POST"
                    :action="mode === 'create' ? '{{ route('super-admin.leave-requests.store') }}' : '{{ url('super-admin/leave-requests') }}/' + form.leave_request_id"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">

                    <div class="ems-modal__header">
                        <h2 class="ems-modal__title" x-text="mode === 'create' ? 'Tambah Pengajuan Cuti' : 'Edit Pengajuan Cuti'"></h2>
                        <button type="button" class="ems-modal__close" @click="closeFormModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="ems-modal__body">
                        <div class="ems-form-group">
                            <label class="ems-form-label" for="leave_request_employee">Karyawan</label>
                            <select id="leave_request_employee" name="employee_id" class="ems-form-control" x-model="form.employee_id" required>
                                <option value="">— Pilih Karyawan —</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->employee_id }}">{{ $employee->full_name }} ({{ $employee->employee_number }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="leave_request_type">Jenis Cuti</label>
                            <select id="leave_request_type" name="leave_type_id" class="ems-form-control" x-model="form.leave_type_id" required>
                                <option value="">— Pilih Jenis Cuti —</option>
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->leave_type_id }}">{{ $leaveType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="leave_request_start">Tanggal Mulai</label>
                            <input id="leave_request_start" type="date" name="start_date" class="ems-form-control" x-model="form.start_date" required>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="leave_request_end">Tanggal Selesai</label>
                            <input id="leave_request_end" type="date" name="end_date" class="ems-form-control" x-model="form.end_date" required>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="leave_request_reason">Alasan</label>
                            <textarea id="leave_request_reason" name="reason" class="ems-form-control" rows="3" x-model="form.reason" placeholder="Contoh: Cuti tahunan keluarga" required></textarea>
                        </div>
                    </div>

                    <div class="ems-modal__footer">
                        <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeFormModal()">Batal</button>
                        <button type="submit" class="ems-btn ems-btn--primary" x-text="mode === 'create' ? 'Simpan' : 'Update'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Confirm Delete --}}
        <div class="ems-modal-overlay" x-show="showDeleteModal" x-cloak @keydown.escape.window="closeDeleteModal()">
            <div class="ems-modal ems-modal--sm" @click.outside="closeDeleteModal()">
                <div class="ems-modal__header">
                    <h2 class="ems-modal__title">Hapus Pengajuan Cuti</h2>
                    <button type="button" class="ems-modal__close" @click="closeDeleteModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="ems-modal__body">
                    <p>Apakah Anda yakin ingin menghapus pengajuan cuti <strong x-text="deleteTarget.name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeDeleteModal()">Batal</button>
                    <form method="POST" :action="'{{ url('super-admin/leave-requests') }}/' + deleteTarget.leave_request_id">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ems-btn-delete-confirm">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection
