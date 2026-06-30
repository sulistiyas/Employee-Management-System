export default function roleManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create', // 'create' | 'edit'
        form: {
            role_id: null,
            name: '',
            slug: '',
            description: '',
        },
        deleteTarget: {
            role_id: null,
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
                role_id: null,
                name: '',
                slug: '',
                description: '',
            };
            this.showFormModal = true;
        },

        openEdit(role) {
            this.mode = 'edit';
            this.form = {
                role_id: role.role_id,
                name: role.name,
                slug: role.slug,
                description: role.description ?? '',
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(role) {
            this.deleteTarget = {
                role_id: role.role_id,
                name: role.name,
            };
            this.showDeleteModal = true;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
        },

        generateSlug() {
            this.form.slug = this.form.name
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
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
            if (confirm(`Hapus ${this.selectedCount} role terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
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
                    const container = document.getElementById('roleTableContainer');
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