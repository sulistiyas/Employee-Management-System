@extends('layouts.app')

@section('title', 'Attendance')

@section('content')

    <div x-data="attendanceManager()">

        {{-- Page header --}}
        <div class="ems-page-header">
            <div>
                <h1 class="ems-page-title">Attendance</h1>
                <p class="ems-page-subtitle">Kelola data absensi karyawan.</p>
            </div>
            <div class="ems-page-header__actions">
                <button type="button" class="ems-btn ems-btn--primary" @click="openCreate()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Absensi
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="ems-card ems-card--flush" @click="handlePaginationClick($event)">

            {{-- Toolbar: search + filter status + filter tanggal --}}
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

                    <input
                        type="date"
                        class="ems-dt-perpage__select"
                        x-model="dateFilter"
                        @change="handleSearch()"
                    >
                </div>

                <div class="ems-dt-toolbar__right">
                    <div class="ems-dt-perpage">
                        <label for="attendance_per_page">Tampilkan</label>
                        <select id="attendance_per_page" class="ems-dt-perpage__select" x-model="perPage" @change="changePerPage()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Bulk action bar --}}
            <div class="ems-dt-bulk" x-show="selectedCount > 0" x-cloak>
                <span class="ems-dt-bulk__count"><span x-text="selectedCount"></span> data terpilih</span>
                <button type="button" class="ems-dt-bulk__btn ems-dt-bulk__btn--danger" @click="deleteSelected()">
                    Hapus Terpilih
                </button>
            </div>

            <form id="bulk-delete-form" method="POST" action="{{ route('super-admin.attendances.bulk-destroy') }}">
                @csrf
                @method('DELETE')
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="attendance_ids[]" :value="id">
                </template>
            </form>

            <div class="ems-card__body">
                <div id="attendanceTableContainer" :class="{ 'ems-table-loading': isLoadingTable }">
                    @include('super-admin.attendances.table', ['attendances' => $attendances])
                </div>
            </div>
        </div>

        {{-- Modal: Create / Edit --}}
        <div class="ems-modal-overlay" x-show="showFormModal" x-cloak @keydown.escape.window="closeFormModal()">
            <div class="ems-modal" @click.outside="closeFormModal()">
                <form
                    method="POST"
                    :action="mode === 'create' ? '{{ route('super-admin.attendances.store') }}' : '{{ url('super-admin/attendances') }}/' + form.attendance_id"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">

                    <div class="ems-modal__header">
                        <h2 class="ems-modal__title" x-text="mode === 'create' ? 'Tambah Absensi' : 'Edit Absensi'"></h2>
                        <button type="button" class="ems-modal__close" @click="closeFormModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="ems-modal__body">
                        <div class="ems-form-group">
                            <label class="ems-form-label" for="attendance_employee">Karyawan</label>
                            <select id="attendance_employee" name="employee_id" class="ems-form-control" x-model="form.employee_id" required>
                                <option value="">— Pilih Karyawan —</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->employee_id }}">{{ $employee->full_name }} ({{ $employee->employee_number }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="attendance_date">Tanggal Absensi</label>
                            <input id="attendance_date" type="date" name="attendance_date" class="ems-form-control" x-model="form.attendance_date" required>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="attendance_status">Status</label>
                            <select id="attendance_status" name="attendance_status" class="ems-form-control" x-model="form.attendance_status">
                                <option value="">— Otomatis dari jam masuk —</option>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <span class="ems-form-hint">Kosongkan untuk menentukan status otomatis berdasarkan jam masuk dan shift karyawan.</span>
                        </div>

                        <div class="ems-form-group" x-show="form.attendance_status !== 'absent' && form.attendance_status !== 'permit'" x-cloak>
                            <label class="ems-form-label" for="attendance_check_in">Jam Masuk</label>
                            <input id="attendance_check_in" type="time" name="check_in" class="ems-form-control" x-model="form.check_in">
                        </div>

                        <div class="ems-form-group" x-show="form.attendance_status !== 'absent' && form.attendance_status !== 'permit'" x-cloak>
                            <label class="ems-form-label" for="attendance_check_out">Jam Keluar</label>
                            <input id="attendance_check_out" type="time" name="check_out" class="ems-form-control" x-model="form.check_out">
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="attendance_notes">Catatan</label>
                            <textarea id="attendance_notes" name="notes" class="ems-form-control" rows="3" x-model="form.notes" placeholder="Catatan tambahan (opsional)"></textarea>
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
                    <h2 class="ems-modal__title">Hapus Absensi</h2>
                    <button type="button" class="ems-modal__close" @click="closeDeleteModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="ems-modal__body">
                    <p>Apakah Anda yakin ingin menghapus data absensi <strong x-text="deleteTarget.name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeDeleteModal()">Batal</button>
                    <form method="POST" :action="'{{ url('super-admin/attendances') }}/' + deleteTarget.attendance_id">
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
