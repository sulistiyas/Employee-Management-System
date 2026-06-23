@extends('layouts.app')

@section('title', 'Shifts')

@section('content')

    <div x-data="shiftManager()">

        {{-- Page header --}}
        <div class="ems-page-header">
            <div>
                <h1 class="ems-page-title">Shifts</h1>
                <p class="ems-page-subtitle">Kelola jadwal shift kerja karyawan.</p>
            </div>
            <div class="ems-page-header__actions">
                <button type="button" class="ems-btn ems-btn--primary" @click="openCreate()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Shift
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="ems-card ems-card--flush" @click="handlePaginationClick($event)">

            {{-- Toolbar: search --}}
            <div class="ems-table-toolbar">
                <div class="ems-search-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ems-search-icon"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>

                    <input
                        type="text"
                        class="ems-search-input"
                        placeholder="Cari nama atau kode shift..."
                        x-model="searchQuery"
                        @input.debounce.400ms="handleSearch()"
                    >

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
                </div>
            </div>

            <div class="ems-card__body">
                <div id="shiftTableContainer" :class="{ 'ems-table-loading': isLoadingTable }">
                    @include('super-admin.shifts.table', ['shifts' => $shifts])
                </div>
            </div>
        </div>

        {{-- Modal: Create / Edit --}}
        <div class="ems-modal-overlay" x-show="showFormModal" x-cloak @keydown.escape.window="closeFormModal()">
            <div class="ems-modal" @click.outside="closeFormModal()">
                <form
                    method="POST"
                    :action="mode === 'create' ? '{{ route('super-admin.shifts.store') }}' : '{{ url('super-admin/shifts') }}/' + form.shift_id"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">

                    <div class="ems-modal__header">
                        <h2 class="ems-modal__title" x-text="mode === 'create' ? 'Tambah Shift' : 'Edit Shift'"></h2>
                        <button type="button" class="ems-modal__close" @click="closeFormModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="ems-modal__body">
                        <div class="ems-form-group">
                            <label class="ems-form-label" for="shift_name">Nama Shift</label>
                            <input
                                id="shift_name"
                                type="text"
                                name="name"
                                class="ems-form-control"
                                x-model="form.name"
                                placeholder="Contoh: Shift Pagi"
                                required
                            >
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="shift_code">Kode Shift</label>
                            <input
                                id="shift_code"
                                type="text"
                                name="code"
                                class="ems-form-control"
                                x-model="form.code"
                                placeholder="Contoh: SHIFT-PAGI"
                                required
                            >
                            <span class="ems-form-hint">Gunakan kode unik untuk identitas shift ini.</span>
                        </div>

                        <div class="ems-form-row">
                            <div class="ems-form-group">
                                <label class="ems-form-label" for="shift_start_time">Jam Mulai</label>
                                <input
                                    id="shift_start_time"
                                    type="time"
                                    name="start_time"
                                    class="ems-form-control"
                                    x-model="form.start_time"
                                    required
                                >
                            </div>

                            <div class="ems-form-group">
                                <label class="ems-form-label" for="shift_end_time">Jam Selesai</label>
                                <input
                                    id="shift_end_time"
                                    type="time"
                                    name="end_time"
                                    class="ems-form-control"
                                    x-model="form.end_time"
                                    required
                                >
                            </div>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="shift_late_tolerance_minutes">Toleransi Telat (menit)</label>
                            <input
                                id="shift_late_tolerance_minutes"
                                type="number"
                                name="late_tolerance_minutes"
                                class="ems-form-control"
                                x-model="form.late_tolerance_minutes"
                                min="0"
                                placeholder="0"
                            >
                            <span class="ems-form-hint">Jumlah menit keterlambatan yang masih ditoleransi sebelum dianggap telat.</span>
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
                    <h2 class="ems-modal__title">Hapus Shift</h2>
                    <button type="button" class="ems-modal__close" @click="closeDeleteModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="ems-modal__body">
                    <p>Apakah Anda yakin ingin menghapus shift <strong x-text="deleteTarget.name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeDeleteModal()">Batal</button>
                    <form method="POST" :action="'{{ url('super-admin/shifts') }}/' + deleteTarget.shift_id">
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