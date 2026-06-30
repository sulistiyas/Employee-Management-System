@extends('layouts.app')

@section('title', 'Detail Shift')

@section('content')

    <div x-data="shiftDetailManager()">

        {{-- Page header --}}
        <div class="ems-page-header">
            <div>
                <a href="{{ route('super-admin.shifts.index') }}" class="ems-breadcrumb-back">&larr; Kembali ke Shifts</a>
                <h1 class="ems-page-title">{{ ucfirst($shift->name) }} ({{ $shift->code }})</h1>
                <p class="ems-page-subtitle">
                    {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                    &ndash;
                    {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                    &middot; Toleransi telat {{ $shift->late_tolerance_minutes }} menit
                </p>
            </div>
            <div class="ems-page-header__actions">
                <button type="button" class="ems-btn ems-btn--primary" @click="openAssign()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Assign Karyawan
                </button>
            </div>
        </div>

        {{-- Table karyawan aktif --}}
        <div class="ems-card ems-card--flush" @click="handlePaginationClick($event)">
            <div class="ems-dt-toolbar">
                <div class="ems-dt-toolbar__left">
                    <div class="ems-dt-search">
                        <span class="ems-dt-search__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </span>
                        <input
                            type="text"
                            class="ems-dt-search__input"
                            placeholder="Cari nama atau no. karyawan..."
                            x-model="searchQuery"
                            @input.debounce.400ms="handleSearch()"
                        >
                    </div>
                </div>
                <div class="ems-dt-toolbar__right">
                    <div class="ems-dt-perpage">
                        <label for="assign_per_page">Tampilkan</label>
                        <select id="assign_per_page" class="ems-dt-perpage__select" x-model="perPage" @change="changePerPage()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div x-show="selectedCount > 0" x-cloak>
                        <button type="button" class="ems-btn ems-btn--danger-soft ems-btn--sm" @click="openRemoveBulk()">
                            Copot <span x-text="selectedCount"></span> Terpilih
                        </button>
                    </div>
                </div>
            </div>

            <div class="ems-card__body">
                <div id="assignmentTableContainer" :class="{ 'ems-table-loading': isLoadingTable }">
                    @include('super-admin.shifts.assignment-table', ['assignments' => $assignments])
                </div>
            </div>
        </div>

        {{-- Modal: Assign Karyawan (bulk) --}}
        <div class="ems-modal-overlay" x-show="showAssignModal" x-cloak @keydown.escape.window="closeAssignModal()">
            <div class="ems-modal" @click.outside="closeAssignModal()">
                <form
                    method="POST"
                    action="{{ route('super-admin.shifts.assign', $shift->shift_id) }}"
                    @submit="prepareAssignSubmit($event)"
                >
                    @csrf

                    <div class="ems-modal__header">
                        <h2 class="ems-modal__title">Assign Karyawan ke {{ ucfirst($shift->name) }}</h2>
                        <button type="button" class="ems-modal__close" @click="closeAssignModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="ems-modal__body">
                        <div class="ems-form-group">
                            <label class="ems-form-label" for="effective_date">Berlaku Sejak</label>
                            <input
                                id="effective_date"
                                type="date"
                                name="effective_date"
                                class="ems-form-control"
                                x-model="assignForm.effective_date"
                                required
                            >
                            <span class="ems-form-hint">Tanggal ini berlaku untuk semua karyawan yang dipilih.</span>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label">Pilih Karyawan</label>
                            <input
                                type="text"
                                class="ems-form-control"
                                placeholder="Cari karyawan yang belum punya shift..."
                                x-model="availableSearch"
                                @input.debounce.400ms="loadAvailableEmployees()"
                            >

                            <div class="ems-checklist">
                                <template x-if="isLoadingAvailable">
                                    <p class="ems-form-hint">Memuat daftar karyawan...</p>
                                </template>
                                <template x-if="!isLoadingAvailable && availableEmployees.length === 0">
                                    <p class="ems-form-hint">Tidak ada karyawan yang tersedia (semua sudah punya shift aktif).</p>
                                </template>
                                <template x-for="employee in availableEmployees" :key="employee.employee_id">
                                    <label class="ems-checklist__item">
                                        <input
                                            type="checkbox"
                                            :value="employee.employee_id"
                                            x-model="assignForm.employee_ids"
                                        >
                                        <span>
                                            <strong x-text="employee.full_name"></strong>
                                            <small x-text="employee.employee_number + ' · ' + (employee.department || '-')"></small>
                                        </span>
                                    </label>
                                </template>
                            </div>
                            <span class="ems-form-hint">
                                <span x-text="assignForm.employee_ids.length"></span> karyawan dipilih.
                            </span>
                        </div>
                    </div>

                    <div class="ems-modal__footer">
                        <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeAssignModal()">Batal</button>
                        <button type="submit" class="ems-btn ems-btn--primary" :disabled="assignForm.employee_ids.length === 0">
                            Assign <span x-text="assignForm.employee_ids.length"></span> Karyawan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Confirm Remove (single & bulk pakai modal yang sama) --}}
        <div class="ems-modal-overlay" x-show="showRemoveModal" x-cloak @keydown.escape.window="closeRemoveModal()">
            <div class="ems-modal ems-modal--sm" @click.outside="closeRemoveModal()">
                <div class="ems-modal__header">
                    <h2 class="ems-modal__title">Copot dari Shift</h2>
                    <button type="button" class="ems-modal__close" @click="closeRemoveModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="ems-modal__body">
                    <template x-if="removeTarget.mode === 'single'">
                        <p>Copot <strong x-text="removeTarget.full_name"></strong> dari shift ini? Karyawan akan menjadi tidak terjadwal di shift manapun.</p>
                    </template>
                    <template x-if="removeTarget.mode === 'bulk'">
                        <p>Copot <strong x-text="selectedCount"></strong> karyawan terpilih dari shift ini? Mereka akan menjadi tidak terjadwal di shift manapun.</p>
                    </template>
                </div>
                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeRemoveModal()">Batal</button>
                    <form method="POST" action="{{ route('super-admin.shifts.remove-assignments', $shift->shift_id) }}" @submit="prepareRemoveSubmit($event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ems-btn-delete-confirm">
                            Copot
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection
@push('scripts')
<script>
    window.shiftDetailRoutes = {
        availableEmployees: '{{ route('super-admin.shifts.available-employees') }}',
    };
</script>
@endpush