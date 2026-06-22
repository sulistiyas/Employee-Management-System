export default function datatableFilter() {
    return {
        search: '',
        perPage: '10',
        showFilters: false,
        selected: [],
        selectedCount: 0,
        allSelected: false,
        sortColumn: '',
        sortDirection: 'asc',
        filters: {
            department: '',
            status: '',
            position: '',
        },

        get activeFilterCount() {
            return Object.values(this.filters).filter(v => v !== '').length;
        },

        applySearch() {
            const params = new URLSearchParams(window.location.search);

            if (this.search) params.set('search', this.search);
            else params.delete('search');

            if (this.filters.department) params.set('department', this.filters.department);
            else params.delete('department');

            if (this.filters.status) params.set('status', this.filters.status);
            else params.delete('status');

            if (this.filters.position) params.set('position', this.filters.position);
            else params.delete('position');

            if (this.sortColumn) {
                params.set('sort', this.sortColumn);
                params.set('dir', this.sortDirection);
            }

            params.delete('page');
            window.location.search = params.toString();
        },

        changePerPage() {
            const params = new URLSearchParams(window.location.search);
            params.set('per_page', this.perPage);
            params.delete('page');
            window.location.search = params.toString();
        },

        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
            this.applySearch();
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

        resetFilters() {
            this.filters = { department: '', status: '', position: '' };
            this.search = '';
            this.applySearch();
        },

        confirmDelete(event) {
            if (confirm('Are you sure you want to delete this employee? This action cannot be undone.')) {
                event.target.submit();
            }
        },

        deleteSelected() {
            if (this.selectedCount === 0) return;
            if (confirm(`Delete ${this.selectedCount} selected employee(s)? This action cannot be undone.`)) {
                const form = document.getElementById('bulk-delete-form');
                if (form) form.submit();
            }
        },

        init() {
            const params = new URLSearchParams(window.location.search);
            this.search        = params.get('search')     || '';
            this.perPage       = params.get('per_page')   || '10';
            this.filters.department = params.get('department') || '';
            this.filters.status     = params.get('status')     || '';
            this.filters.position   = params.get('position')   || '';
            this.sortColumn    = params.get('sort') || '';
            this.sortDirection = params.get('dir')  || 'asc';

            if (this.activeFilterCount > 0) {
                this.showFilters = true;
            }
        },
    };
}