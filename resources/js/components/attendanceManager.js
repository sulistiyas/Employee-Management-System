export default function attendanceManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create', // 'create' | 'edit'
        form: {
            attendance_id: null,
            employee_id: '',
            attendance_date: '',
            check_in: '',
            check_out: '',
            attendance_status: '',
            notes: '',
        },
        deleteTarget: {
            attendance_id: null,
            name: '',
        },

        isLoadingTable: false,
        searchQuery: '',
        statusFilter: '',
        dateFilter: '',
        perPage: '10',

        selected: [],
        selectedCount: 0,
        allSelected: false,

        init() {
            this.searchQuery = new URLSearchParams(window.location.search).get('search') || '';
            this.statusFilter = new URLSearchParams(window.location.search).get('status') || '';
            this.dateFilter = new URLSearchParams(window.location.search).get('date') || '';
            this.perPage = new URLSearchParams(window.location.search).get('per_page') || '10';

            window.addEventListener('popstate', () => {
                this.loadTable(window.location.href);
            });
        },

        openCreate() {
            this.mode = 'create';
            this.form = {
                attendance_id: null,
                employee_id: '',
                attendance_date: '',
                check_in: '',
                check_out: '',
                attendance_status: '',
                notes: '',
            };
            this.showFormModal = true;
        },

        openEdit(attendance) {
            this.mode = 'edit';
            this.form = {
                attendance_id: attendance.attendance_id,
                employee_id: attendance.employee_id,
                attendance_date: attendance.attendance_date,
                check_in: attendance.check_in,
                check_out: attendance.check_out,
                attendance_status: attendance.attendance_status,
                notes: attendance.notes,
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(attendance) {
            this.deleteTarget = {
                attendance_id: attendance.attendance_id,
                name: attendance.name,
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

            if (this.dateFilter) {
                url.searchParams.set('date', this.dateFilter);
            } else {
                url.searchParams.delete('date');
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
                    document.getElementById('attendanceTableContainer').innerHTML = response.data;
                    window.Alpine.initTree(document.getElementById('attendanceTableContainer'));
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
            if (confirm(`Hapus ${this.selectedCount} data absensi terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.getElementById('bulk-delete-form');
                if (form) form.submit();
            }
        },
    };
}
