@extends('layouts.app')

@section('title', 'Employees')

@section('content')

    <div x-data="employeeManager()">

        {{-- Page header --}}
        <div class="ems-page-header">
            <div>
                <h1 class="ems-page-title">Employees</h1>
                <p class="ems-page-subtitle">Kelola data karyawan dalam sistem.</p>
            </div>
            <div class="ems-page-header__actions">
                <button type="button" class="ems-btn ems-btn--primary" @click="openCreate()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Employee
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="ems-card ems-card--flush" @click="handlePaginationClick($event)">
            <div class="ems-table-toolbar">
                <div class="ems-search-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ems-search-icon"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>

                    <input
                        type="text"
                        class="ems-search-input"
                        placeholder="Cari nama, nomor employee, atau telepon..."
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
                <div id="employeeTableContainer" :class="{ 'ems-table-loading': isLoadingTable }">
                    @include('super-admin.employees.table', ['employees' => $employees])
                </div>
            </div>
        </div>

        <x-pagination :paginator="$employees" />

        {{-- Modal: Create / Edit --}}
        <div class="ems-modal-overlay" x-show="showFormModal" x-cloak @keydown.escape.window="closeFormModal()">
            <div class="ems-modal ems-modal--lg" @click.outside="closeFormModal()">
                <form
                    method="POST"
                    :action="mode === 'create' ? '{{ route('super-admin.employees.store') }}' : '{{ url('super-admin/employees') }}/' + form.employee_id"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">

                    <div class="ems-modal__header">
                        <h2 class="ems-modal__title" x-text="mode === 'create' ? 'Tambah Employee' : 'Edit Employee'"></h2>
                        <button type="button" class="ems-modal__close" @click="closeFormModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="ems-modal__body">
                        <div class="ems-form-group">
                            <label class="ems-form-label" for="emp_number">Nomor Employee</label>
                            <input
                                id="emp_number"
                                type="text"
                                name="employee_number"
                                class="ems-form-control"
                                x-model="form.employee_number"
                                placeholder="Contoh: EMP001"
                                required
                            >
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="emp_name">Nama Lengkap</label>
                            <input
                                id="emp_name"
                                type="text"
                                name="full_name"
                                class="ems-form-control"
                                x-model="form.full_name"
                                placeholder="Contoh: John Doe"
                                required
                            >
                        </div>

                        <div class="ems-form-row">
                            <div class="ems-form-group">
                                <label class="ems-form-label" for="emp_gender">Jenis Kelamin</label>
                                <select
                                    id="emp_gender"
                                    name="gender"
                                    class="ems-form-control"
                                    x-model="form.gender"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>

                            <div class="ems-form-group">
                                <label class="ems-form-label" for="emp_birth">Tanggal Lahir</label>
                                <input
                                    id="emp_birth"
                                    type="date"
                                    name="birth_date"
                                    class="ems-form-control"
                                    x-model="form.birth_date"
                                    required
                                >
                            </div>
                        </div>

                        <div class="ems-form-row">
                            <div class="ems-form-group">
                                <label class="ems-form-label" for="emp_phone">Telepon</label>
                                <input
                                    id="emp_phone"
                                    type="tel"
                                    name="phone"
                                    class="ems-form-control"
                                    x-model="form.phone"
                                    placeholder="Contoh: 081234567890"
                                >
                            </div>

                            <div class="ems-form-group">
                                <label class="ems-form-label" for="emp_join">Tanggal Bergabung</label>
                                <input
                                    id="emp_join"
                                    type="date"
                                    name="join_date"
                                    class="ems-form-control"
                                    x-model="form.join_date"
                                    required
                                >
                            </div>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="emp_address">Alamat</label>
                            <textarea
                                id="emp_address"
                                name="address"
                                class="ems-form-control"
                                x-model="form.address"
                                placeholder="Alamat lengkap (opsional)"
                            ></textarea>
                        </div>

                        <div class="ems-form-row">
                            <div class="ems-form-group">
                                <label class="ems-form-label" for="emp_dept">Departemen</label>
                                <select
                                    id="emp_dept"
                                    name="department_id"
                                    class="ems-form-control"
                                    x-model="form.department_id"
                                    required
                                >
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->department_id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="ems-form-group">
                                <label class="ems-form-label" for="emp_pos">Posisi</label>
                                <select
                                    id="emp_pos"
                                    name="position_id"
                                    class="ems-form-control"
                                    x-model="form.position_id"
                                    required
                                >
                                    <option value="">Pilih Posisi</option>
                                    @foreach ($positions as $pos)
                                        <option value="{{ $pos->position_id }}">{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="emp_status">Status Pekerjaan</label>
                            <select
                                id="emp_status"
                                name="employment_status"
                                class="ems-form-control"
                                x-model="form.employment_status"
                                required
                            >
                                <option value="active">Aktif</option>
                                <option value="resigned">Resign</option>
                                <option value="terminated">Terminated</option>
                            </select>
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
                    <h2 class="ems-modal__title">Hapus Employee</h2>
                    <button type="button" class="ems-modal__close" @click="closeDeleteModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="ems-modal__body">
                    <p>Apakah Anda yakin ingin menghapus employee <strong x-text="deleteTarget.full_name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeDeleteModal()">Batal</button>
                    <form method="POST" :action="'{{ url('super-admin/employees') }}/' + deleteTarget.employee_id">
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
