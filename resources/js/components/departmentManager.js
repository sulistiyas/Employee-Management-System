export default function departmentManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create', // 'create' | 'edit'
        form: {
            department_id: null,
            name: '',
            code: '',
            description: '',
            manager_employee_id: '',
            hr_employee_id: '',
        },
        deleteTarget: {
            department_id: null,
            name: '',
        },

        isLoadingTable: false,
        searchQuery: '',
        perPage: '10',

        selected: [],
        selectedCount: 0,
        allSelected: false,
        sortColumn: '',
        sortDirection: 'asc',

        init() {
            const params = new URLSearchParams(window.location.search);
            this.searchQuery = params.get('search') || '';
            this.perPage = params.get('per_page') || '10';
            this.sortColumn = params.get('sort') || '';
            this.sortDirection = params.get('dir') || 'asc';

            window.addEventListener('popstate', () => {
                this.loadTable(window.location.href);
            });
        },

        openCreate() {
            this.mode = 'create';
            this.form = {
                department_id: null,
                name: '',
                code: '',
                description: '',
                manager_employee_id: '',
                hr_employee_id: '',
            };
            this.showFormModal = true;
        },

        openEdit(department) {
            this.mode = 'edit';
            this.form = {
                department_id: department.department_id,
                name: department.name,
                code: department.code,
                description: department.description ?? '',
                manager_employee_id: department.manager_employee_id ?? '',
                hr_employee_id: department.hr_employee_id ?? '',
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(department) {
            this.deleteTarget = {
                department_id: department.department_id,
                name: department.name,
            };
            this.showDeleteModal = true;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
        },

        generateCode() {
            this.form.code = this.form.name
                .toUpperCase()
                .trim()
                .replace(/[^A-Z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        },

        buildUrl() {
            const url = new URL(window.location.href);

            if (this.searchQuery) url.searchParams.set('search', this.searchQuery);
            else url.searchParams.delete('search');

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

        changePerPage() {
            this.loadTable(this.buildUrl().toString());
        },

        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
            this.loadTable(this.buildUrl().toString());
        },

        getSortClass(column) {
            if (this.sortColumn !== column) return '';
            return this.sortDirection === 'asc' ? 'ems-dt__sort-icon--asc' : 'ems-dt__sort-icon--desc';
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
                    const container = document.getElementById('departmentTableContainer');
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
            if (confirm(`Hapus ${this.selectedCount} departemen terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.getElementById('bulk-delete-form');
                if (form) form.submit();
            }
        },
    };
}