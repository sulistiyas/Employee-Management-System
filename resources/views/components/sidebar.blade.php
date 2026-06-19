<aside class="ems-sidebar" :class="sidebarOpen ? 'ems-sidebar--open' : 'ems-sidebar--closed'" x-cloak>

    {{-- Brand --}}
    <div class="ems-sidebar__brand">
        <div class="ems-sidebar__logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        </div>
        <span class="ems-sidebar__brand-name" x-show="sidebarOpen">EMS</span>
    </div>

    {{-- Navigation --}}
    <nav class="ems-sidebar__nav">

        {{-- Overview --}}
        <div class="ems-sidebar__section">
            <span class="ems-sidebar__section-label" x-show="sidebarOpen">Overview</span>

            <a href="{{ route('super-admin.dashboard') }}"
               class="ems-sidebar__link {{ request()->routeIs('super-admin.dashboard') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Dashboard</span>
            </a>
        </div>

        {{-- HR Management --}}
        <div class="ems-sidebar__section">
            <span class="ems-sidebar__section-label" x-show="sidebarOpen">HR Management</span>

            <a href="#"
            {{-- <a href="{{ route('super-admin.employees.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.employees.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Employees</span>
            </a>

            <a href="#"
            {{-- <a href="{{ route('super-admin.departments.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.departments.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Departments</span>
            </a>

            <a href="#"
            {{-- <a href="{{ route('super-admin.positions.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.positions.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Positions</span>
            </a>
        </div>

        {{-- Attendance --}}
        <div class="ems-sidebar__section">
            <span class="ems-sidebar__section-label" x-show="sidebarOpen">Attendance</span>

            <a href="#"
            {{-- <a href="{{ route('super-admin.attendances.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.attendances.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Attendance</span>
            </a>

            <a href="#"
            {{-- <a href="{{ route('super-admin.shifts.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.shifts.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Shifts</span>
            </a>

            <a href="#"
            {{-- <a href="{{ route('super-admin.holidays.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.holidays.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Holidays</span>
            </a>
        </div>

        {{-- Leave --}}
        <div class="ems-sidebar__section">
            <span class="ems-sidebar__section-label" x-show="sidebarOpen">Leave</span>

            <a href="#"
            {{-- <a href="{{ route('super-admin.leave-requests.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.leave-requests.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Leave Requests</span>
            </a>

            <a href="#"
            {{-- <a href="{{ route('super-admin.leave-types.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.leave-types.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Leave Types</span>
            </a>
        </div>

        {{-- System --}}
        <div class="ems-sidebar__section">
            <span class="ems-sidebar__section-label" x-show="sidebarOpen">System</span>

            <a href="#"
            {{-- <a href="{{ route('super-admin.users.index') }}" --}}
               class="ems-sidebar__link {{ request()->routeIs('super-admin.users.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Users</span>
            </a>

            <a href="{{ route('super-admin.roles.index') }}"
               class="ems-sidebar__link {{ request()->routeIs('super-admin.roles.*') ? 'ems-sidebar__link--active' : '' }}">
                <span class="ems-sidebar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                </span>
                <span class="ems-sidebar__label" x-show="sidebarOpen">Roles</span>
            </a>
        </div>

    </nav>

    {{-- Collapse toggle --}}
    <button class="ems-sidebar__toggle" @click="sidebarOpen = !sidebarOpen">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="sidebarOpen"><polyline points="15 18 9 12 15 6"/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="!sidebarOpen"><polyline points="9 18 15 12 9 6"/></svg>
    </button>

</aside>