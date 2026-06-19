@extends('layouts.app')

@section('title', 'Roles')

@section('content')

    <div x-data="roleManager()">

        {{-- Page header --}}
        <div class="ems-page-header">
            <div>
                <h1 class="ems-page-title">Roles</h1>
                <p class="ems-page-subtitle">Kelola role dan hak akses pengguna pada sistem.</p>
            </div>
            <div class="ems-page-header__actions">
                <button type="button" class="ems-btn ems-btn--primary" @click="openCreate()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Role
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="ems-card ems-card--flush">
            <div class="ems-card__body">
                <div class="ems-table-wrap">
                    <table class="ems-table">
                        <thead>
                            <tr>
                                <th>Nama Role</th>
                                <th>Slug</th>
                                <th>Deskripsi</th>
                                <th>Jumlah User</th>
                                <th class="ems-table__th-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td class="ems-table__name">{{ $role->name }}</td>
                                    <td>
                                        <span class="ems-pill ems-pill--leave">{{ $role->slug }}</span>
                                    </td>
                                    <td class="ems-table__muted">{{ $role->description ?: '-' }}</td>
                                    <td>{{ $role->users_count ?? 0 }}</td>
                                    <td>
                                        <div class="ems-table__actions">
                                            <button
                                                type="button"
                                                class="ems-icon-btn ems-icon-btn--edit"
                                                title="Edit"
                                                @click="openEdit({
                                                    role_id: {{ $role->role_id }},
                                                    name: @js($role->name),
                                                    slug: @js($role->slug),
                                                    description: @js($role->description),
                                                })"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                            <button
                                                type="button"
                                                class="ems-icon-btn ems-icon-btn--delete"
                                                title="Hapus"
                                                @click="openDelete({
                                                    role_id: {{ $role->role_id }},
                                                    name: @js($role->name),
                                                })"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="ems-empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                            <p>Belum ada role</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal: Create / Edit --}}
        <div class="ems-modal-overlay" x-show="showFormModal" x-cloak @keydown.escape.window="closeFormModal()">
            <div class="ems-modal" @click.outside="closeFormModal()">
                <form
                    method="POST"
                    :action="mode === 'create' ? '{{ route('super-admin.roles.store') }}' : '{{ url('super-admin/roles') }}/' + form.role_id"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">

                    <div class="ems-modal__header">
                        <h2 class="ems-modal__title" x-text="mode === 'create' ? 'Tambah Role' : 'Edit Role'"></h2>
                        <button type="button" class="ems-modal__close" @click="closeFormModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="ems-modal__body">
                        <div class="ems-form-group">
                            <label class="ems-form-label" for="role_name">Nama Role</label>
                            <input
                                id="role_name"
                                type="text"
                                name="name"
                                class="ems-form-control"
                                x-model="form.name"
                                @input="mode === 'create' && generateSlug()"
                                placeholder="Contoh: Manager"
                                required
                            >
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="role_slug">Slug</label>
                            <input
                                id="role_slug"
                                type="text"
                                name="slug"
                                class="ems-form-control"
                                x-model="form.slug"
                                placeholder="Contoh: manager"
                                required
                            >
                            <span class="ems-form-hint">Digunakan sistem untuk pengecekan hak akses, gunakan huruf kecil tanpa spasi.</span>
                        </div>

                        <div class="ems-form-group">
                            <label class="ems-form-label" for="role_description">Deskripsi</label>
                            <textarea
                                id="role_description"
                                name="description"
                                class="ems-form-control"
                                x-model="form.description"
                                placeholder="Deskripsi singkat mengenai role ini (opsional)"
                            ></textarea>
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
                    <h2 class="ems-modal__title">Hapus Role</h2>
                    <button type="button" class="ems-modal__close" @click="closeDeleteModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="ems-modal__body">
                    <p>Apakah Anda yakin ingin menghapus role <strong x-text="deleteTarget.name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeDeleteModal()">Batal</button>
                    <form method="POST" :action="'{{ url('super-admin/roles') }}/' + deleteTarget.role_id">
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