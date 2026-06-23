export default function shiftManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create', // 'create' | 'edit'
        form: {
            shift_id: null,
            code: '',
            name: '',
            start_time: '',
            end_time: '',
            late_tolerance_minutes: 0,
        },
        deleteTarget: {
            shift_id: null,
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
                shift_id: null,
                code: '',
                name: '',
                start_time: '',
                end_time: '',
                late_tolerance_minutes: 0,
            };
            this.showFormModal = true;
        },

        openEdit(shift) {
            this.mode = 'edit';
            this.form = {
                shift_id: shift.shift_id,
                code: shift.code,
                name: shift.name,
                start_time: shift.start_time,
                end_time: shift.end_time,
                late_tolerance_minutes: shift.late_tolerance_minutes,
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(shift) {
            this.deleteTarget = {
                shift_id: shift.shift_id,
                name: shift.name,
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
                    document.getElementById('shiftTableContainer').innerHTML = response.data;
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
