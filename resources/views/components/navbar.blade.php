<header class="ems-navbar">

    {{-- Left: hamburger + breadcrumb --}}
    <div class="ems-navbar__left">
        <button class="ems-navbar__hamburger" @click="sidebarOpen = !sidebarOpen">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>

        <div class="ems-navbar__breadcrumb">
            <span class="ems-navbar__breadcrumb-root">EMS</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span class="ems-navbar__breadcrumb-current">@yield('title', 'Dashboard')</span>
        </div>
    </div>

    {{-- Right: actions + profile --}}
    <div class="ems-navbar__right">

        {{-- Search --}}
        <div class="ems-navbar__search" x-data="{ open: false }">
            <button class="ems-navbar__icon-btn" @click="open = !open">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
            <div class="ems-navbar__search-dropdown" x-show="open" @click.outside="open = false" x-cloak>
                <input type="text" placeholder="Search employees, departments..." class="ems-navbar__search-input" autofocus>
            </div>
        </div>

        {{-- Notifications --}}
        <div class="ems-navbar__notif" x-data="{ open: false, count: 3 }">
            <button class="ems-navbar__icon-btn ems-navbar__icon-btn--badge" @click="open = !open">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span class="ems-navbar__badge" x-show="count > 0" x-text="count"></span>
            </button>

            <div class="ems-navbar__dropdown" x-show="open" @click.outside="open = false" x-cloak>
                <div class="ems-navbar__dropdown-header">
                    <span>Notifications</span>
                    <button class="ems-navbar__dropdown-action" @click="count = 0">Mark all read</button>
                </div>
                <div class="ems-navbar__notif-list">
                    <a href="#" class="ems-navbar__notif-item ems-navbar__notif-item--unread">
                        <div class="ems-navbar__notif-dot"></div>
                        <div class="ems-navbar__notif-body">
                            <p>New leave request from <strong>John Doe</strong></p>
                            <span>5 minutes ago</span>
                        </div>
                    </a>
                    <a href="#" class="ems-navbar__notif-item ems-navbar__notif-item--unread">
                        <div class="ems-navbar__notif-dot"></div>
                        <div class="ems-navbar__notif-body">
                            <p><strong>Attendance report</strong> for May is ready</p>
                            <span>1 hour ago</span>
                        </div>
                    </a>
                    <a href="#" class="ems-navbar__notif-item ems-navbar__notif-item--unread">
                        <div class="ems-navbar__notif-dot"></div>
                        <div class="ems-navbar__notif-body">
                            <p>3 employees have <strong>late check-in</strong> today</p>
                            <span>2 hours ago</span>
                        </div>
                    </a>
                </div>
                <div class="ems-navbar__dropdown-footer">
                    <a href="#">View all notifications</a>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="ems-navbar__divider"></div>

        {{-- Profile --}}
        <div class="ems-navbar__profile" x-data="{ open: false }">
            <button class="ems-navbar__profile-btn" @click="open = !open">
                <div class="ems-navbar__avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="ems-navbar__profile-info">
                    <span class="ems-navbar__profile-name">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <span class="ems-navbar__profile-role">Super Admin</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="open ? 'ems-rotate-180' : ''"><polyline points="6 9 12 15 18 9"/></svg>
            </button>

            <div class="ems-navbar__dropdown ems-navbar__dropdown--right" x-show="open" @click.outside="open = false" x-cloak>
                <div class="ems-navbar__dropdown-header">
                    <div class="ems-navbar__avatar ems-navbar__avatar--lg">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="ems-navbar__profile-name">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="ems-navbar__profile-email">{{ auth()->user()->email ?? 'admin@ems.com' }}</p>
                    </div>
                </div>
                <div class="ems-navbar__dropdown-body">
                    <a href="#" class="ems-navbar__dropdown-item">
                    {{-- <a href="{{ route('super-admin.profile') }}" class="ems-navbar__dropdown-item"> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        My Profile
                    </a>
                    <a href="#" class="ems-navbar__dropdown-item">
                    {{-- <a href="{{ route('super-admin.settings') }}" class="ems-navbar__dropdown-item"> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        Settings
                    </a>
                </div>
                <div class="ems-navbar__dropdown-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="ems-navbar__logout-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</header>