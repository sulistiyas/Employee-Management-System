export default function shiftManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create',
        form: {
            shift_id: null,
            name: '',          
            code: '',          
            start_time: '',
            end_time: '',
            late_tolerance_minutes: 0,
        },
        isLoadingCode: false,  
        deleteTarget: { shift_id: null, name: '' },

        isLoadingTable: false,
        searchQuery: '',
        perPage: '10',
        selected: [],
        selectedCount: 0,
        allSelected: false,

        init() {
            const params = new URLSearchParams(window.location.search);
            this.searchQuery = params.get('search') || '';
            this.perPage     = params.get('per_page') || '10';

            window.addEventListener('popstate', () => {
                this.loadTable(window.location.href);
            });
        },

        openCreate() {
            this.mode = 'create';
            this.form = {
                shift_id: null,
                name: '',
                code: '',
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
                name: shift.name,           
                code: shift.code,
                start_time: shift.start_time,
                end_time: shift.end_time,
                late_tolerance_minutes: shift.late_tolerance_minutes,
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        
        async onTypeChange() {
            if (this.mode !== 'create' || !this.form.name) {
                this.form.code = '';
                return;
            }

            this.isLoadingCode = true;
            this.form.code = '';

            try {
                const response = await window.axios.get(
                    window.shiftRoutes.nextCode,
                    { params: { type: this.form.name } }
                );
                this.form.code = response.data.code;
            } catch (e) {
                this.form.code = 'Error';
            } finally {
                this.isLoadingCode = false;
            }
        },

        openDelete(shift) {
            this.deleteTarget = { shift_id: shift.shift_id, name: shift.name };
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

        changePerPage() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.perPage);
            url.searchParams.delete('page');
            this.loadTable(url.toString());
        },

        handlePaginationClick(event) {
            const link = event.target.closest('.ems-pagination__btn[href]');
            if (!link) return;
            event.preventDefault();
            this.loadTable(link.href);
        },

        loadTable(url) {
            this.isLoadingTable = true;
            window.axios
                .get(url)
                .then((response) => {
                    const container = document.getElementById('shiftTableContainer');
                    container.innerHTML = response.data;
                    window.Alpine.initTree(container);
                    window.history.pushState({}, '', url);
                    this.selected = [];
                    this.selectedCount = 0;
                    this.allSelected = false;
                })
                .catch(() => { window.location.href = url; })
                .finally(() => { this.isLoadingTable = false; });
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
            if (confirm(`Hapus ${this.selectedCount} shift terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.getElementById('bulk-delete-form');
                if (form) form.submit();
            }
        },
    };
}