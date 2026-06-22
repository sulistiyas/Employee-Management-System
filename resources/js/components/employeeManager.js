export default function employeeManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create',
        form: {
            employee_id: null,
            employee_number: '',
            full_name: '',
            gender: '',
            birth_date: '',
            phone: '',
            address: '',
            join_date: '',
            employment_status: 'active',
            department_id: null,
            position_id: null,
        },
        deleteTarget: {
            employee_id: null,
            full_name: '',
        },

        isLoadingTable: false,
        searchQuery: '',
        perPage: '10',
        showFilters: false,
        filters: {
            department: '',
            status: '',
            position: '',
        },

        selected: [],
        selectedCount: 0,
        allSelected: false,
        sortColumn: '',
        sortDirection: 'asc',

        get activeFilterCount() {
            return Object.values(this.filters).filter(v => v !== '').length;
        },

        init() {
            const params = new URLSearchParams(window.location.search);
            this.searchQuery = params.get('search') || '';
            this.perPage = params.get('per_page') || '10';
            this.filters.department = params.get('department') || '';
            this.filters.status = params.get('status') || '';
            this.filters.position = params.get('position') || '';
            this.sortColumn = params.get('sort') || '';
            this.sortDirection = params.get('dir') || 'asc';

            if (this.activeFilterCount > 0) {
                this.showFilters = true;
            }

            window.addEventListener('popstate', () => {
                this.loadTable(window.location.href);
            });
        },

        openCreate() {
            this.mode = 'create';
            this.form = {
                employee_id: null,
                employee_number: '',
                full_name: '',
                gender: '',
                birth_date: '',
                phone: '',
                address: '',
                join_date: '',
                employment_status: 'active',
                department_id: null,
                position_id: null,
            };
            this.showFormModal = true;
        },

        openEdit(employee) {
            this.mode = 'edit';
            this.form = {
                employee_id: employee.employee_id,
                employee_number: employee.employee_number,
                full_name: employee.full_name,
                gender: employee.gender,
                birth_date: employee.birth_date,
                phone: employee.phone ?? '',
                address: employee.address ?? '',
                join_date: employee.join_date,
                employment_status: employee.employment_status,
                department_id: employee.department_id,
                position_id: employee.position_id,
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(employee) {
            this.deleteTarget = {
                employee_id: employee.employee_id,
                full_name: employee.full_name,
            };
            this.showDeleteModal = true;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
        },

        buildUrl() {
            const url = new URL(window.location.href);

            if (this.searchQuery) url.searchParams.set('search', this.searchQuery);
            else url.searchParams.delete('search');

            if (this.filters.department) url.searchParams.set('department', this.filters.department);
            else url.searchParams.delete('department');

            if (this.filters.status) url.searchParams.set('status', this.filters.status);
            else url.searchParams.delete('status');

            if (this.filters.position) url.searchParams.set('position', this.filters.position);
            else url.searchParams.delete('position');

            if (this.sortColumn) {
                url.searchParams.set('sort', this.sortColumn);
                url.searchParams.set('dir', this.sortDirection);
            }

            url.searchParams.set('per_page', this.perPage);
            url.searchParams.delete('page');

            return url;
        },

        handleSearch() {
            this.loadTable(this.buildUrl().toString());
        },

        clearSearch() {
            this.searchQuery = '';
            this.handleSearch();
        },

        applyFilters() {
            this.loadTable(this.buildUrl().toString());
        },

        changePerPage() {
            this.loadTable(this.buildUrl().toString());
        },

        resetFilters() {
            this.filters = { department: '', status: '', position: '' };
            this.searchQuery = '';
            this.applyFilters();
        },

        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
            this.applyFilters();
        },

        getSortClass(column) {
            if (this.sortColumn !== column) return '';
            return this.sortDirection === 'asc' ? 'ems-dt__sort-icon--asc' : 'ems-dt__sort-icon--desc';
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
            if (confirm(`Hapus ${this.selectedCount} employee terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.getElementById('bulk-delete-form');
                if (form) form.submit();
            }
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
                    const container = document.getElementById('employeeTableContainer');
                    container.innerHTML = response.data;
                    window.Alpine.initTree(container);
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
    };
}