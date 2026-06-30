export default function leaveRequestManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create', // 'create' | 'edit'
        form: {
            leave_request_id: null,
            employee_id: '',
            leave_type_id: '',
            start_date: '',
            end_date: '',
            reason: '',
        },
        deleteTarget: {
            leave_request_id: null,
            name: '',
        },

        isLoadingTable: false,
        searchQuery: '',
        statusFilter: '',
        perPage: '10',

        selected: [],
        selectedCount: 0,
        allSelected: false,

        init() {
            this.searchQuery = new URLSearchParams(window.location.search).get('search') || '';
            this.statusFilter = new URLSearchParams(window.location.search).get('status') || '';
            this.perPage = new URLSearchParams(window.location.search).get('per_page') || '10';

            window.addEventListener('popstate', () => {
                this.loadTable(window.location.href);
            });
        },

        openCreate() {
            this.mode = 'create';
            this.form = {
                leave_request_id: null,
                employee_id: '',
                leave_type_id: '',
                start_date: '',
                end_date: '',
                reason: '',
            };
            this.showFormModal = true;
        },

        openEdit(leaveRequest) {
            this.mode = 'edit';
            this.form = {
                leave_request_id: leaveRequest.leave_request_id,
                employee_id: leaveRequest.employee_id,
                leave_type_id: leaveRequest.leave_type_id,
                start_date: leaveRequest.start_date,
                end_date: leaveRequest.end_date,
                reason: leaveRequest.reason,
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(leaveRequest) {
            this.deleteTarget = {
                leave_request_id: leaveRequest.leave_request_id,
                name: leaveRequest.name,
            };
            this.showDeleteModal = true;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
        },

        handleSearch() {
            const url = new URL(window.location.href);

            if (this.searchQuery) {
                url.searchParams.set('search', this.searchQuery);
            } else {
                url.searchParams.delete('search');
            }

            if (this.statusFilter) {
                url.searchParams.set('status', this.statusFilter);
            } else {
                url.searchParams.delete('status');
            }

            url.searchParams.delete('page');

            this.loadTable(url.toString());
        },

        clearSearch() {
            this.searchQuery = '';
            this.handleSearch();
        },

        changePerPage() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.perPage);
            url.searchParams.delete('page');

            this.loadTable(url.toString());
        },

        handlePaginationClick(event) {
            const link = event.target.closest('.ems-pagination__btn[href]');

            if (!link) {
                return;
            }

            event.preventDefault();
            this.loadTable(link.href);
        },

        loadTable(url) {
            this.isLoadingTable = true;

            window.axios
                .get(url)
                .then((response) => {
                    document.getElementById('leaveRequestTableContainer').innerHTML = response.data;
                    window.Alpine.initTree(document.getElementById('leaveRequestTableContainer'));
                    window.history.pushState({}, '', url);
                    this.selected = [];
                    this.selectedCount = 0;
                    this.allSelected = false;
                })
                .catch(() => {
                    window.location.href = url;
                })
                .finally(() => {
                    this.isLoadingTable = false;
                });
        },

        toggleAll(event) {
            const checkboxes = document.querySelectorAll('tbody .ems-dt__check');
            this.selected = event.target.checked
                ? Array.from(checkboxes).map(cb => cb.value)
                : [];
            this.selectedCount = this.selected.length;
            this.allSelected = event.target.checked;
        },

        updateCount() {
            this.selectedCount = this.selected.length;
            const allCheckboxes = document.querySelectorAll('tbody .ems-dt__check');
            this.allSelected = this.selectedCount === allCheckboxes.length;
        },

        deleteSelected() {
            if (this.selectedCount === 0) return;
            if (confirm(`Hapus ${this.selectedCount} pengajuan cuti terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.getElementById('bulk-delete-form');
                if (form) form.submit();
            }
        },
    };
}
