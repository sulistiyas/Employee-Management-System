export default function positionManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create', // 'create' | 'edit'
        form: {
            position_id: null,
            name: '',
            level: '',
            department_id: null,
        },
        deleteTarget: {
            position_id: null,
            name: '',
        },

        isLoadingTable: false,
        searchQuery: '',
        perPage: '10',

        selected: [],
        selectedCount: 0,
        allSelected: false,

        init() {
            this.searchQuery = new URLSearchParams(window.location.search).get('search') || '';
            this.perPage = new URLSearchParams(window.location.search).get('per_page') || '10';

            window.addEventListener('popstate', () => {
                this.loadTable(window.location.href);
            });
        },

        openCreate() {
            this.mode = 'create';
            this.form = {
                position_id: null,
                name: '',
                level: '',
                department_id: null,
            };
            this.showFormModal = true;
        },

        openEdit(position) {
            this.mode = 'edit';
            this.form = {
                position_id: position.position_id,
                name: position.name,
                level: position.level,
                department_id: position.department_id,
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(position) {
            this.deleteTarget = {
                position_id: position.position_id,
                name: position.name,
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
            url.searchParams.delete('page');

            this.loadTable(url.toString());
        },

        clearSearch() {
            this.searchQuery = '';
            this.handleSearch();
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
                    document.getElementById('positionTableContainer').innerHTML = response.data;
                    window.Alpine.initTree(document.getElementById('positionTableContainer'));
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
            if (confirm(`Hapus ${this.selectedCount} posisi terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.getElementById('bulk-delete-form');
                if (form) form.submit();
            }
        },
    };
}
