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
        },
        deleteTarget: {
            department_id: null,
            name: '',
        },

        isLoadingTable: false,
        searchQuery: '',

        init() {
            this.searchQuery = new URLSearchParams(window.location.search).get('search') || '';

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
                    document.getElementById('departmentTableContainer').innerHTML = response.data;
                    window.history.pushState({}, '', url);
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
