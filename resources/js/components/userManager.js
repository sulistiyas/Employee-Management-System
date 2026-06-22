export default function userManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create',
        form: {
            id: null,
            name: '',
            email: '',
            password: '',
            role_id: null,
            employee_id: null,
            is_active: true,
        },
        deleteTarget: {
            id: null,
            name: '',
        },

        isLoadingTable: false,
        searchQuery: '',
        perPage: '10',
        showFilters: false,
        filters: {
            role: '',
            active: '',
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
            this.filters.role = params.get('role') || '';
            this.filters.active = params.get('active') || '';
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
                id: null,
                name: '',
                email: '',
                password: '',
                role_id: null,
                employee_id: null,
                is_active: true,
            };
            this.showFormModal = true;
        },

        openEdit(user) {
            this.mode = 'edit';
            this.form = {
                id: user.id,
                name: user.name,
                email: user.email,
                password: '',
                role_id: user.role_id,
                employee_id: user.employee_id ?? null,
                is_active: user.is_active,
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(user) {
            this.deleteTarget = {
                id: user.id,
                name: user.name,
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

            if (this.filters.role) url.searchParams.set('role', this.filters.role);
            else url.searchParams.delete('role');

            if (this.filters.active !== '') url.searchParams.set('active', this.filters.active);
            else url.searchParams.delete('active');

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
            this.filters = { role: '', active: '' };
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
                    document.getElementById('userTableContainer').innerHTML = response.data;
                    window.Alpine.initTree(document.getElementById('userTableContainer'));
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
            if (confirm(`Hapus ${this.selectedCount} user terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.getElementById('bulk-delete-form');
                if (form) form.submit();
            }
        },
    };
}
