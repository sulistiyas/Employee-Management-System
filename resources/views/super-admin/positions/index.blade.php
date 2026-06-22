@extends('layouts.app')

@section('title', 'Positions')

@section('content')

    <div x-data="positionManager()">

        {{-- Page header --}}
        <div class="ems-page-header">
            <div>
                <h1 class="ems-page-title">Positions</h1>
                <p class="ems-page-subtitle">Kelola posisi karyawan dalam organisasi Anda.</p>
            </div>
            <div class="ems-page-header__actions">
                <button type="button" class="ems-btn ems-btn--primary" @click="openCreate()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Posisi
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="ems-card ems-card--flush" @click="handlePaginationClick($event)">

            {{-- Toolbar: search + per-page --}}
            <div class="ems-dt-toolbar">
                <div class="ems-dt-toolbar__left">
                    <div class="ems-dt-search">
                        <span class="ems-dt-search__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </span>
                        <input
                            type="text"
                            class="ems-dt-search__input"
                            placeholder="Cari nama posisi atau level..."
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
                    <div class="ems-dt-perpage">
                        <label for="pos_per_page">Tampilkan</label>
                        <select id="pos_per_page" class="ems-dt-perpage__select" x-model="perPage" @change="changePerPage()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Bulk action bar --}}
            <div class="ems-dt-bulk" x-show="selectedCount > 0" x-cloak>
                <span class="ems-dt-bulk__count"><span x-text="selectedCount"></span> posisi terpilih</span>
                <button type="button" class="ems-dt-bulk__btn ems-dt-bulk__btn--danger" @click="deleteSelected()">
                    Hapus Terpilih
                </button>
            </div>

            <form id="bulk-delete-form" method="POST" action="{{ route('super-admin.positions.bulk-destroy') }}">
                @csrf
                @method('DELETE')
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="position_ids[]" :value="id">
                </template>
            </form>

            <div class="ems-card__body">
                <div id="positionTableContainer" :class="{ 'ems-table-loading': isLoadingTable }">
                    @include('super-admin.positions.table', ['positions' => $positions])
                </div>
            </div>
        </div>

        {{-- Modal: Create / Edit --}}
        <div class="ems-modal-overlay" x-show="showFormModal" x-cloak @keydown.escape.window="closeFormModal()">
            <div class="ems-modal" @click.outside="closeFormModal()">
                <form
                    method="POST"
                    :action="mode === 'create' ? '{{ route('super-admin.positions.store') }}' : '{{ url('super-admin/positions') }}/' + form.position_id"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">

                    <div class="ems-modal__header">
                        <h2 class="ems-modal__title" x-text="mode === 'create' ? 'Tambah Posisi' : 'Edit Posisi'"></h2>
                        <button type="button" class="ems-modal__close" @click="closeFormModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="ems-modal__body">
                        <div class="ems-form-group">
                            <label class="ems-form-label" for="position_name">Nama Posisi</label>
                            <input
                                id="position_name"
                                type="text"
                                name="name"
                                class="ems-form-control"
                                x-model="form.name"
                                placeholder="Contoh: Senior Developer"
                                required
                            >
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="position_level">Level Posisi</label>
                            <input
                                id="position_level"
                                type="text"
                                name="level"
                                class="ems-form-control"
                                x-model="form.level"
                                placeholder="Contoh: Senior, Junior, Lead"
                                required
                            >
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="position_department">Departemen</label>
                            <select
                                id="position_department"
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
                    <h2 class="ems-modal__title">Hapus Posisi</h2>
                    <button type="button" class="ems-modal__close" @click="closeDeleteModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="ems-modal__body">
                    <p>Apakah Anda yakin ingin menghapus posisi <strong x-text="deleteTarget.name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeDeleteModal()">Batal</button>
                    <form method="POST" :action="'{{ url('super-admin/positions') }}/' + deleteTarget.position_id">
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
