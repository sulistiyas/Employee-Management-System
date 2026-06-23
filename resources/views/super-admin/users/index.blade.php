@extends('layouts.app')

@section('title', 'Users')

@section('content')

    <div x-data="userManager()">

        {{-- Page header --}}
        <div class="ems-page-header">
            <div>
                <h1 class="ems-page-title">Users</h1>
                <p class="ems-page-subtitle">Kelola akun pengguna sistem.</p>
            </div>
            <div class="ems-page-header__actions">
                <button type="button" class="ems-btn ems-btn--primary" @click="openCreate()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah User
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="ems-card ems-card--flush" @click="handlePaginationClick($event)">

            {{-- Toolbar: search + filter toggle + per-page --}}
            <div class="ems-dt-toolbar">
                <div class="ems-dt-toolbar__left">
                    <div class="ems-dt-search">
                        <span class="ems-dt-search__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </span>
                        <input
                            type="text"
                            class="ems-dt-search__input"
                            placeholder="Cari nama atau email user..."
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
                </div>

                <div class="ems-dt-toolbar__right">
                    <button
                        type="button"
                        class="ems-dt-filter-btn"
                        :class="{ 'ems-dt-filter-btn--active': showFilters }"
                        @click="showFilters = !showFilters"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter
                        <span x-show="activeFilterCount > 0" x-cloak class="ems-dt-filter-badge" x-text="activeFilterCount"></span>
                    </button>

                    <div class="ems-dt-perpage">
                        <label for="user_per_page">Tampilkan</label>
                        <select id="user_per_page" class="ems-dt-perpage__select" x-model="perPage" @change="changePerPage()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Collapsible filter row --}}
            <div class="ems-dt-filters" x-show="showFilters" x-cloak>
                <select class="ems-dt-filters__select" x-model="filters.role" @change="applyFilters()">
                    <option value="">Semua Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->role_id }}" @selected(request('role') == $role->role_id)>{{ $role->name }}</option>
                    @endforeach
                </select>

                <select class="ems-dt-filters__select" x-model="filters.active" @change="applyFilters()">
                    <option value="">Semua Status</option>
                    <option value="1" @selected(request('active') === '1')>Aktif</option>
                    <option value="0" @selected(request('active') === '0')>Tidak Aktif</option>
                </select>

                <button type="button" class="ems-dt-filter-btn" x-show="activeFilterCount > 0" x-cloak @click="resetFilters()">
                    Reset Filter
                </button>
            </div>

            {{-- Bulk action bar --}}
            <div class="ems-dt-bulk" x-show="selectedCount > 0" x-cloak>
                <span class="ems-dt-bulk__count"><span x-text="selectedCount"></span> user terpilih</span>
                <button type="button" class="ems-dt-bulk__btn ems-dt-bulk__btn--danger" @click="deleteSelected()">
                    Hapus Terpilih
                </button>
            </div>

            <form id="bulk-delete-form" method="POST" action="{{ route('super-admin.users.bulk-destroy') }}">
                @csrf
                @method('DELETE')
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="user_ids[]" :value="id">
                </template>
            </form>

            <div class="ems-card__body">
                <div id="userTableContainer" :class="{ 'ems-table-loading': isLoadingTable }">
                    @include('super-admin.users.table', ['users' => $users])
                </div>
            </div>
        </div>
        {{-- Modal: Create / Edit --}}
        <div class="ems-modal-overlay" x-show="showFormModal" x-cloak @keydown.escape.window="closeFormModal()">
            <div class="ems-modal" @click.outside="closeFormModal()">
                <form
                    method="POST"
                    :action="mode === 'create' ? '{{ route('super-admin.users.store') }}' : '{{ url('super-admin/users') }}/' + form.id"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">

                    <div class="ems-modal__header">
                        <h2 class="ems-modal__title" x-text="mode === 'create' ? 'Tambah User' : 'Edit User'"></h2>
                        <button type="button" class="ems-modal__close" @click="closeFormModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="ems-modal__body">
                        <div class="ems-form-group" x-show="mode === 'create'">
                            <label class="ems-form-label" for="user_employee">Karyawan (opsional)</label>
                            <select
                                id="user_employee"
                                :name="mode === 'create' ? 'employee_id' : ''"
                                class="ems-form-control"
                                x-model="form.employee_id"
                                @change="onEmployeeChange($event)"
                            >
                                <option value="">— Buat akun tanpa data karyawan —</option>
                                @foreach ($availableEmployees as $employee)
                                    <option
                                        value="{{ $employee->employee_id }}"
                                        data-full-name="{{ $employee->full_name }}"
                                        data-employee-number="{{ $employee->employee_number }}"
                                        data-department="{{ $employee->department?->name ?? '-' }}"
                                        data-position="{{ $employee->position?->name ?? '-' }}"
                                    >
                                        {{ $employee->full_name }} ({{ $employee->employee_number }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="ems-form-hint">Pilih karyawan untuk membuatkan akun login. Nama akan terisi otomatis dan dapat diubah jika perlu.</p>
                        </div>

                        <div class="ems-form-group" x-show="mode === 'edit' && form.employee_id" x-cloak>
                            <label class="ems-form-label">Karyawan Terhubung</label>
                            <input type="text" class="ems-form-control" :value="form.employee_full_name" disabled>
                            <input type="hidden" name="employee_id" :value="form.employee_id">
                            <p class="ems-form-hint">Karyawan yang terhubung tidak dapat diubah setelah akun dibuat.</p>
                        </div>

                        <div class="ems-form-group" x-show="mode === 'create' && form.employee_id" x-cloak>
                            <label class="ems-form-label">Info Karyawan</label>
                            <div class="ems-form-hint">
                                Departemen: <strong x-text="employeeInfo.department"></strong> ·
                                Posisi: <strong x-text="employeeInfo.position"></strong>
                            </div>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="user_name">Nama User</label>
                            <input
                                id="user_name"
                                type="text"
                                name="name"
                                class="ems-form-control"
                                x-model="form.name"
                                placeholder="Contoh: John Doe"
                                required
                            >
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="user_email">Email</label>
                            <input
                                id="user_email"
                                type="email"
                                name="email"
                                class="ems-form-control"
                                x-model="form.email"
                                placeholder="Contoh: john@example.com"
                                required
                            >
                        </div>

                        <div class="ems-form-group" x-show="mode === 'create'">
                            <label class="ems-form-label" for="user_password">Password</label>
                            <input
                                id="user_password"
                                type="password"
                                name="password"
                                class="ems-form-control"
                                x-model="form.password"
                                placeholder="Minimal 8 karakter"
                                :required="mode === 'create'"
                            >
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="user_role">Role</label>
                            <select
                                id="user_role"
                                name="role_id"
                                class="ems-form-control"
                                x-model="form.role_id"
                                required
                            >
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->role_id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="user_active">
                                <input
                                    id="user_active"
                                    type="checkbox"
                                    name="is_active"
                                    x-model="form.is_active"
                                >
                                Aktif
                            </label>
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
                    <h2 class="ems-modal__title">Hapus User</h2>
                    <button type="button" class="ems-modal__close" @click="closeDeleteModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="ems-modal__body">
                    <p>Apakah Anda yakin ingin menghapus user <strong x-text="deleteTarget.name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeDeleteModal()">Batal</button>
                    <form method="POST" :action="'{{ url('super-admin/users') }}/' + deleteTarget.id">
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