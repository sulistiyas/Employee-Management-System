@extends('layouts.app')

@section('title', 'Employees')

@section('content')

    {{-- Page Header --}}
    <div class="ems-page-header">
        <div>
            <h1 class="ems-page-title">Employees</h1>
            <p class="ems-page-subtitle">Manage all employee data in your organization</p>
        </div>
        <div class="ems-page-header__actions">
            <a href="{{ route('super-admin.employees.create') }}" class="ems-btn ems-btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Employee
            </a>
        </div>
    </div>

    {{-- Datatable Card --}}
    <div class="ems-card" x-data="datatableFilter()">

        {{-- Toolbar --}}
        <div class="ems-dt-toolbar">
            <div class="ems-dt-toolbar__left">

                {{-- Search --}}
                <div class="ems-dt-search">
                    <span class="ems-dt-search__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        class="ems-dt-search__input"
                        placeholder="Search by name, email, or ID..."
                        x-model="search"
                        @input.debounce.300ms="applySearch()"
                    >
                </div>

                {{-- Per page --}}
                <div class="ems-dt-perpage">
                    <span>Show</span>
                    <select class="ems-dt-perpage__select" x-model="perPage" @change="changePerPage()">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>

            </div>

            <div class="ems-dt-toolbar__right">

                {{-- Filter toggle --}}
                <button
                    class="ems-dt-filter-btn"
                    :class="showFilters ? 'ems-dt-filter-btn--active' : ''"
                    @click="showFilters = !showFilters"
                    type="button"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    Filters
                    <span class="ems-dt-filter-badge" x-show="activeFilterCount > 0" x-text="activeFilterCount"></span>
                </button>

                {{-- Export --}}
                <a href="{{ route('super-admin.employees.export') }}" class="ems-dt-filter-btn" title="Export">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export
                </a>

            </div>
        </div>

        {{-- Filter panel --}}
        <div class="ems-dt-filters" x-show="showFilters" x-cloak>
            <select class="ems-dt-filters__select" x-model="filters.department" @change="applySearch()">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>

            <select class="ems-dt-filters__select" x-model="filters.status" @change="applySearch()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <select class="ems-dt-filters__select" x-model="filters.position" @change="applySearch()">
                <option value="">All Positions</option>
                @foreach($positions as $pos)
                    <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                @endforeach
            </select>

            <button type="button" class="ems-dt-filter-btn" @click="resetFilters()">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.85"/>
                </svg>
                Reset Filters
            </button>
        </div>

        {{-- Bulk action bar --}}
        <div class="ems-dt-bulk" x-show="selectedCount > 0" x-cloak>
            <span class="ems-dt-bulk__count">
                <span x-text="selectedCount"></span> employee(s) selected
            </span>
            <button type="button" class="ems-dt-bulk__btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export Selected
            </button>
            <button type="button" class="ems-dt-bulk__btn ems-dt-bulk__btn--danger" @click="deleteSelected()">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
                Delete Selected
            </button>
        </div>

        {{-- Table --}}
        <div class="ems-dt-wrap">
            <table class="ems-dt">
                <thead>
                    <tr>
                        <th class="ems-dt__checkbox">
                            <input type="checkbox" class="ems-dt__check" @change="toggleAll($event)" :checked="allSelected">
                        </th>
                        <th>
                            <span class="ems-dt__sort" @click="sortBy('name')">
                                Employee
                                <span class="ems-dt__sort-icon" :class="getSortClass('name')">
                                    <svg width="8" height="5" viewBox="0 0 8 5" class="ems-dt__sort-up"><path d="M4 0L8 5H0z" fill="currentColor"/></svg>
                                    <svg width="8" height="5" viewBox="0 0 8 5" class="ems-dt__sort-down"><path d="M4 5L0 0H8z" fill="currentColor"/></svg>
                                </span>
                            </span>
                        </th>
                        <th>
                            <span class="ems-dt__sort" @click="sortBy('employee_id')">
                                ID
                                <span class="ems-dt__sort-icon" :class="getSortClass('employee_id')">
                                    <svg width="8" height="5" viewBox="0 0 8 5" class="ems-dt__sort-up"><path d="M4 0L8 5H0z" fill="currentColor"/></svg>
                                    <svg width="8" height="5" viewBox="0 0 8 5" class="ems-dt__sort-down"><path d="M4 5L0 0H8z" fill="currentColor"/></svg>
                                </span>
                            </span>
                        </th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>
                            <span class="ems-dt__sort" @click="sortBy('join_date')">
                                Join Date
                                <span class="ems-dt__sort-icon" :class="getSortClass('join_date')">
                                    <svg width="8" height="5" viewBox="0 0 8 5" class="ems-dt__sort-up"><path d="M4 0L8 5H0z" fill="currentColor"/></svg>
                                    <svg width="8" height="5" viewBox="0 0 8 5" class="ems-dt__sort-down"><path d="M4 5L0 0H8z" fill="currentColor"/></svg>
                                </span>
                            </span>
                        </th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td class="ems-dt__checkbox">
                                <input
                                    type="checkbox"
                                    class="ems-dt__check"
                                    value="{{ $employee->id }}"
                                    x-model="selected"
                                    @change="updateCount()"
                                >
                            </td>
                            <td>
                                <div class="ems-dt-avatar">
                                    @if($employee->photo)
                                        <img src="{{ asset('storage/' . $employee->photo) }}"
                                             alt="{{ $employee->full_name }}"
                                             class="ems-dt-avatar__img">
                                    @else
                                        <div class="ems-dt-avatar__initials">
                                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ems-dt-avatar__info">
                                        <span class="ems-dt-avatar__name">{{ $employee->full_name }}</span>
                                        <span class="ems-dt-avatar__sub">{{ $employee->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="ems-badge ems-badge--info">{{ $employee->employee_id }}</span>
                            </td>
                            <td>{{ $employee->department->name ?? '—' }}</td>
                            <td>{{ $employee->position->name ?? '—' }}</td>
                            <td>{{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d M Y') : '—' }}</td>
                            <td>
                                @if($employee->status === 'active')
                                    <span class="ems-badge ems-badge--active ems-badge--dot">Active</span>
                                @else
                                    <span class="ems-badge ems-badge--inactive ems-badge--dot">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="ems-dt-actions">
                                    <a href="{{ route('super-admin.employees.show', $employee->id) }}"
                                       class="ems-dt-action ems-dt-action--view"
                                       title="View detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('super-admin.employees.edit', $employee->id) }}"
                                       class="ems-dt-action ems-dt-action--edit"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('super-admin.employees.destroy', $employee->id) }}"
                                          @submit.prevent="confirmDelete($event)">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ems-dt-action ems-dt-action--delete" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="ems-dt__empty">
                                <div class="ems-dt__empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                </div>
                                <p class="ems-dt__empty-text">No employees found</p>
                                <p class="ems-dt__empty-sub">Try adjusting your search or filter to find what you're looking for.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: info + pagination --}}
        <div class="ems-dt-footer">
            <p class="ems-dt-info">
                Showing
                <strong>{{ $employees->firstItem() ?? 0 }}</strong>
                to
                <strong>{{ $employees->lastItem() ?? 0 }}</strong>
                of
                <strong>{{ $employees->total() }}</strong>
                employees
            </p>

            {{ $employees->onEachSide(1)->links('components.pagination') }}
        </div>

    </div>

@endsection