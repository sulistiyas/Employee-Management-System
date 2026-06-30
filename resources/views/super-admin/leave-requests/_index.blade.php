<div x-data="leaveApprovalManager('{{ $rolePrefix }}')">

    {{-- Page header --}}
    <div class="ems-page-header">
        <div>
            <h1 class="ems-page-title">{{ $pageTitle }}</h1>
            <p class="ems-page-subtitle">{{ $pageSubtitle }}</p>
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
                    placeholder="Cari nama karyawan..."
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
            <div id="leaveRequestTableContainer" :class="{ 'ems-table-loading': isLoadingTable }">
                @include('leave-requests._table', ['leaveRequests' => $leaveRequests, 'rolePrefix' => $rolePrefix])
            </div>
        </div>
    </div>

    <x-pagination :paginator="$leaveRequests" />

    {{-- Modal: Confirm Reject --}}
    <div class="ems-modal-overlay" x-show="showRejectModal" x-cloak @keydown.escape.window="closeRejectModal()">
        <div class="ems-modal ems-modal--sm" @click.outside="closeRejectModal()">
            <form method="POST" :action="'{{ url($rolePrefix . '/leave-requests') }}/' + rejectTarget.leave_request_id + '/reject'">
                @csrf

                <div class="ems-modal__header">
                    <h2 class="ems-modal__title">Tolak Pengajuan Cuti</h2>
                    <button type="button" class="ems-modal__close" @click="closeRejectModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <div class="ems-modal__body">
                    <p>Tolak pengajuan cuti dari <strong x-text="rejectTarget.employee_name"></strong>?</p>

                    <div class="ems-form-group">
                        <label class="ems-form-label" for="rejection_reason">Alasan Penolakan</label>
                        <textarea
                            id="rejection_reason"
                            name="rejection_reason"
                            class="ems-form-control"
                            x-model="rejectionReason"
                            placeholder="Jelaskan alasan penolakan..."
                            required
                        ></textarea>
                    </div>
                </div>

                <div class="ems-modal__footer">
                    <button type="button" class="ems-btn ems-btn--ghost ems-btn--sm" @click="closeRejectModal()">Batal</button>
                    <button type="submit" class="ems-btn-delete-confirm">Tolak</button>
                </div>
            </form>
        </div>
    </div>

</div>