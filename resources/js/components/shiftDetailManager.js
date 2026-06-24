export default function shiftDetailManager() {
    return {
        // Table karyawan aktif di shift ini
        isLoadingTable: false,
        searchQuery: '',
        selected: [],
        selectedCount: 0,
        allSelected: false,

        // Modal assign
        showAssignModal: false,
        assignForm: {
            effective_date: '',
            employee_ids: [],
        },
        availableEmployees: [],
        availableSearch: '',
        isLoadingAvailable: false,

        // Modal remove (dipakai untuk single & bulk)
        showRemoveModal: false,
        removeTarget: { mode: 'single', employee_id: null, full_name: '' },

        init() {
            const params = new URLSearchParams(window.location.search);
            this.searchQuery = params.get('search') || '';

            window.addEventListener('popstate', () => {
                this.loadTable(window.location.href);
            });
        },

        // ── Table: search & pagination ──
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

        loadTable(url) {
            this.isLoadingTable = true;
            window.axios
                .get(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then((response) => {
                    const container = document.getElementById('assignmentTableContainer');
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

        handlePaginationClick(event) {
            const link = event.target.closest('.ems-pagination__btn[href]');
            if (!link) return;
            event.preventDefault();
            this.loadTable(link.href);
        },

        // ── Table: selection ──
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

        // ── Modal: Assign ──
        openAssign() {
            this.assignForm = {
                effective_date: new Date().toISOString().slice(0, 10),
                employee_ids: [],
            };
            this.availableSearch = '';
            this.showAssignModal = true;
            this.loadAvailableEmployees();
        },

        closeAssignModal() {
            this.showAssignModal = false;
        },

        loadAvailableEmployees() {
            this.isLoadingAvailable = true;
            window.axios
                .get(window.shiftDetailRoutes.availableEmployees, {
                    params: { search: this.availableSearch },
                })
                .then((response) => {
                    this.availableEmployees = response.data.employees;
                })
                .catch(() => {
                    this.availableEmployees = [];
                })
                .finally(() => {
                    this.isLoadingAvailable = false;
                });
        },

        // employee_ids dikelola lewat Alpine state (bukan name="..[]" statis di
        // HTML), karena daftar checkbox-nya hasil render dinamis dari AJAX.
        // Sebelum submit, generate hidden input satu-satu agar terkirim sebagai
        // form data biasa.
        prepareAssignSubmit(event) {
            const form = event.target;

            form.querySelectorAll('input[name="employee_ids[]"]').forEach(el => el.remove());

            this.assignForm.employee_ids.forEach((employeeId) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'employee_ids[]';
                input.value = employeeId;
                form.appendChild(input);
            });
        },

        // ── Modal: Remove ──
        openRemoveSingle(employee) {
            this.removeTarget = { mode: 'single', employee_id: employee.employee_id, full_name: employee.full_name };
            this.showRemoveModal = true;
        },

        openRemoveBulk() {
            if (this.selectedCount === 0) return;
            this.removeTarget = { mode: 'bulk', employee_id: null, full_name: '' };
            this.showRemoveModal = true;
        },

        closeRemoveModal() {
            this.showRemoveModal = false;
        },

        prepareRemoveSubmit(event) {
            const form = event.target;

            form.querySelectorAll('input[name="employee_ids[]"]').forEach(el => el.remove());

            const ids = this.removeTarget.mode === 'single'
                ? [this.removeTarget.employee_id]
                : this.selected;

            ids.forEach((employeeId) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'employee_ids[]';
                input.value = employeeId;
                form.appendChild(input);
            });
        },
    };
}
