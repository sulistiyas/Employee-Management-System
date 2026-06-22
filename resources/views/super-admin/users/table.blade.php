<div class="ems-dt-wrap">
    <table class="ems-dt">
        <thead>
            <tr>
                <th class="ems-dt__checkbox">
                    <input type="checkbox" class="ems-dt__check" @change="toggleAll($event)" :checked="allSelected">
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('name')">
                        Nama User
                        <span class="ems-dt__sort-icon" :class="getSortClass('name')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('email')">
                        Email
                        <span class="ems-dt__sort-icon" :class="getSortClass('email')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('role')">
                        Role
                        <span class="ems-dt__sort-icon" :class="getSortClass('role')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="ems-dt__sort" @click="sortBy('is_active')">
                        Status
                        <span class="ems-dt__sort-icon" :class="getSortClass('is_active')">
                            <svg class="ems-dt__sort-up" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6l6 8H6z"/></svg>
                            <svg class="ems-dt__sort-down" width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18l-6-8h12z"/></svg>
                        </span>
                    </span>
                </th>
                <th class="ems-table__th-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="ems-dt__checkbox">
                        <input
                            type="checkbox"
                            class="ems-dt__check"
                            :value="{{ $user->id }}"
                            x-model="selected"
                            @change="updateCount()"
                        >
                    </td>
                    <td class="ems-table__name">{{ $user->name }}</td>
                    <td class="ems-table__muted">{{ $user->email }}</td>
                    <td>
                        <span class="ems-pill ems-pill--leave">{{ $user->role?->name ?? '-' }}</span>
                    </td>
                    <td>
                        @if ($user->is_active)
                            <span class="ems-pill ems-pill--success">Aktif</span>
                        @else
                            <span class="ems-pill ems-pill--danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="ems-dt-actions">
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--edit"
                                title="Edit"
                                @click="openEdit({
                                    id: {{ $user->id }},
                                    name: @js($user->name),
                                    email: @js($user->email),
                                    role_id: {{ $user->role_id }},
                                    employee_id: {{ $user->employee_id ?? 'null' }},
                                    is_active: {{ $user->is_active ? 'true' : 'false' }},
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button
                                type="button"
                                class="ems-dt-action ems-dt-action--delete"
                                title="Hapus"
                                @click="openDelete({
                                    id: {{ $user->id }},
                                    name: @js($user->name),
                                })"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="ems-dt__empty">
                            <div class="ems-dt__empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <p class="ems-dt__empty-text">
                                @if (request('search'))
                                    User tidak ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada user
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
        Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong>-<strong>{{ $users->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $users->total() }}</strong> user
    </div>
    <x-pagination :paginator="$users" />
</div>