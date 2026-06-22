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

        init() {
            this.searchQuery = new URLSearchParams(window.location.search).get('search') || '';

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
                    document.getElementById('userTableContainer').innerHTML = response.data;
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
