@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Page header --}}
    <div class="ems-page-header">
        <div>
            <h1 class="ems-page-title">Dashboard</h1>
            <p class="ems-page-subtitle">Welcome back, {{ Auth::user()->name ?? 'Admin' }}. Here's what's happening today.</p>
        </div>
        <div class="ems-page-header__actions">
            <span class="ems-badge ems-badge--date">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ now()->format('l, d F Y') }}
            </span>
        </div>
    </div>

    {{-- Stats cards --}}
    <div class="ems-stats-grid">

        <div class="ems-stat-card ems-stat-card--blue">
            <div class="ems-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="ems-stat-card__body">
                <span class="ems-stat-card__label">Total Employees</span>
                <span class="ems-stat-card__value">{{ $stats['total_employees'] ?? 0 }}</span>
                <span class="ems-stat-card__trend ems-stat-card__trend--up">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                    +{{ $stats['new_employees_this_month'] ?? 0 }} this month
                </span>
            </div>
        </div>

        <div class="ems-stat-card ems-stat-card--sky">
            <div class="ems-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="ems-stat-card__body">
                <span class="ems-stat-card__label">Present Today</span>
                <span class="ems-stat-card__value">{{ $stats['present_today'] ?? 0 }}</span>
                <span class="ems-stat-card__trend ems-stat-card__trend--neutral">
                    {{ $stats['attendance_rate'] ?? 0 }}% attendance rate
                </span>
            </div>
        </div>

        <div class="ems-stat-card ems-stat-card--indigo">
            <div class="ems-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="ems-stat-card__body">
                <span class="ems-stat-card__label">Pending Leave</span>
                <span class="ems-stat-card__value">{{ $stats['pending_leave'] ?? 0 }}</span>
                <span class="ems-stat-card__trend ems-stat-card__trend--warning">
                    Needs approval
                </span>
            </div>
        </div>

        <div class="ems-stat-card ems-stat-card--slate">
            <div class="ems-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="ems-stat-card__body">
                <span class="ems-stat-card__label">Departments</span>
                <span class="ems-stat-card__value">{{ $stats['total_departments'] ?? 0 }}</span>
                <span class="ems-stat-card__trend ems-stat-card__trend--neutral">
                    {{ $stats['total_positions'] ?? 0 }} positions total
                </span>
            </div>
        </div>

    </div>

    {{-- Middle row: attendance overview + quick actions --}}
    <div class="ems-dashboard-row">

        {{-- Attendance today breakdown --}}
        <div class="ems-card ems-card--lg">
            <div class="ems-card__header">
                <div>
                    <h2 class="ems-card__title">Today's Attendance</h2>
                    <p class="ems-card__subtitle">{{ now()->format('d F Y') }}</p>
                </div>
                <a href="{{ route('superadmin.attendances.index') }}" class="ems-btn ems-btn--ghost ems-btn--sm">
                    View all
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
            <div class="ems-card__body">

                {{-- Attendance bar --}}
                <div class="ems-attend-bar">
                    <div class="ems-attend-bar__fill ems-attend-bar__fill--present"
                         style="width: {{ ($stats['attendance_rate'] ?? 80) }}%"></div>
                </div>

                {{-- Breakdown --}}
                <div class="ems-attend-breakdown">
                    <div class="ems-attend-item">
                        <span class="ems-attend-dot ems-attend-dot--present"></span>
                        <span class="ems-attend-item__label">Present</span>
                        <span class="ems-attend-item__val">{{ $stats['present_today'] ?? 0 }}</span>
                    </div>
                    <div class="ems-attend-item">
                        <span class="ems-attend-dot ems-attend-dot--late"></span>
                        <span class="ems-attend-item__label">Late</span>
                        <span class="ems-attend-item__val">{{ $stats['late_today'] ?? 0 }}</span>
                    </div>
                    <div class="ems-attend-item">
                        <span class="ems-attend-dot ems-attend-dot--leave"></span>
                        <span class="ems-attend-item__label">On Leave</span>
                        <span class="ems-attend-item__val">{{ $stats['on_leave_today'] ?? 0 }}</span>
                    </div>
                    <div class="ems-attend-item">
                        <span class="ems-attend-dot ems-attend-dot--absent"></span>
                        <span class="ems-attend-item__label">Absent</span>
                        <span class="ems-attend-item__val">{{ $stats['absent_today'] ?? 0 }}</span>
                    </div>
                </div>

                {{-- Recent attendance list --}}
                <div class="ems-attend-list">
                    @forelse ($recentAttendances ?? [] as $attendance)
                        <div class="ems-attend-row">
                            <div class="ems-attend-row__avatar">
                                {{ strtoupper(substr($attendance->employee->full_name ?? 'E', 0, 1)) }}
                            </div>
                            <div class="ems-attend-row__info">
                                <span class="ems-attend-row__name">{{ $attendance->employee->full_name ?? '-' }}</span>
                                <span class="ems-attend-row__dept">{{ $attendance->employee->department->name ?? '-' }}</span>
                            </div>
                            <div class="ems-attend-row__time">
                                <span>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</span>
                            </div>
                            <span class="ems-pill ems-pill--{{ strtolower($attendance->attendance_status) }}">
                                {{ ucfirst($attendance->attendance_status) }}
                            </span>
                        </div>
                    @empty
                        <div class="ems-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <p>No attendance records yet today</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- Right column --}}
        <div class="ems-dashboard-col">

            {{-- Quick actions --}}
            <div class="ems-card">
                <div class="ems-card__header">
                    <h2 class="ems-card__title">Quick Actions</h2>
                </div>
                <div class="ems-card__body">
                    <div class="ems-quick-actions">
                        <a href="{{ route('superadmin.employees.create') }}" class="ems-quick-action">
                            <div class="ems-quick-action__icon ems-quick-action__icon--blue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                            </div>
                            <span>Add Employee</span>
                        </a>
                        <a href="{{ route('superadmin.leave-requests.index') }}" class="ems-quick-action">
                            <div class="ems-quick-action__icon ems-quick-action__icon--indigo">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <span>Leave Requests</span>
                        </a>
                        <a href="{{ route('superadmin.attendances.index') }}" class="ems-quick-action">
                            <div class="ems-quick-action__icon ems-quick-action__icon--sky">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <span>Attendance</span>
                        </a>
                        <a href="{{ route('superadmin.departments.create') }}" class="ems-quick-action">
                            <div class="ems-quick-action__icon ems-quick-action__icon--slate">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </div>
                            <span>Add Department</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Pending leave requests --}}
            <div class="ems-card">
                <div class="ems-card__header">
                    <h2 class="ems-card__title">Pending Approvals</h2>
                    <a href="{{ route('superadmin.leave-requests.index') }}" class="ems-btn ems-btn--ghost ems-btn--sm">
                        View all
                    </a>
                </div>
                <div class="ems-card__body">
                    @forelse ($pendingLeaves ?? [] as $leave)
                        <div class="ems-leave-item">
                            <div class="ems-leave-item__avatar">
                                {{ strtoupper(substr($leave->employee->full_name ?? 'E', 0, 1)) }}
                            </div>
                            <div class="ems-leave-item__info">
                                <span class="ems-leave-item__name">{{ $leave->employee->full_name ?? '-' }}</span>
                                <span class="ems-leave-item__meta">{{ $leave->leaveType->name ?? '-' }} · {{ $leave->total_days }} day(s)</span>
                            </div>
                            <div class="ems-leave-item__actions">
                                <form method="POST" action="{{ route('superadmin.leave-requests.approve', $leave->leave_request_id) }}" style="display:inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="ems-icon-btn ems-icon-btn--approve" title="Approve">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('superadmin.leave-requests.reject', $leave->leave_request_id) }}" style="display:inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="ems-icon-btn ems-icon-btn--reject" title="Reject">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="ems-empty ems-empty--sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <p>No pending leave requests</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- Bottom row: department overview + recent employees --}}
    <div class="ems-dashboard-row ems-dashboard-row--equal">

        {{-- Department overview --}}
        <div class="ems-card">
            <div class="ems-card__header">
                <h2 class="ems-card__title">Department Overview</h2>
                <a href="{{ route('superadmin.departments.index') }}" class="ems-btn ems-btn--ghost ems-btn--sm">View all</a>
            </div>
            <div class="ems-card__body">
                @forelse ($departments ?? [] as $dept)
                    <div class="ems-dept-row">
                        <div class="ems-dept-row__info">
                            <span class="ems-dept-row__name">{{ $dept->name }}</span>
                            <span class="ems-dept-row__count">{{ $dept->employees_count ?? 0 }} employees</span>
                        </div>
                        <div class="ems-dept-bar-wrap">
                            <div class="ems-dept-bar">
                                <div class="ems-dept-bar__fill"
                                     style="width: {{ $dept->employees_count > 0 ? min(100, ($dept->employees_count / max($stats['total_employees'] ?? 1, 1)) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="ems-empty ems-empty--sm">
                        <p>No departments found</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent employees --}}
        <div class="ems-card">
            <div class="ems-card__header">
                <h2 class="ems-card__title">Recent Employees</h2>
                <a href="{{ route('superadmin.employees.index') }}" class="ems-btn ems-btn--ghost ems-btn--sm">View all</a>
            </div>
            <div class="ems-card__body">
                @forelse ($recentEmployees ?? [] as $employee)
                    <div class="ems-emp-row">
                        <div class="ems-emp-row__avatar">
                            @if ($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}">
                            @else
                                {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="ems-emp-row__info">
                            <span class="ems-emp-row__name">{{ $employee->full_name }}</span>
                            <span class="ems-emp-row__meta">{{ $employee->position->name ?? '-' }} · {{ $employee->department->name ?? '-' }}</span>
                        </div>
                        <span class="ems-pill ems-pill--{{ $employee->employment_status === 'active' ? 'present' : 'absent' }}">
                            {{ ucfirst($employee->employment_status) }}
                        </span>
                    </div>
                @empty
                    <div class="ems-empty ems-empty--sm">
                        <p>No employees yet</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

@endsection