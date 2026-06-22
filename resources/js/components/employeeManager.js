export default function employeeManager() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        mode: 'create',
        form: {
            employee_id: null,
            employee_number: '',
            full_name: '',
            gender: '',
            birth_date: '',
            phone: '',
            address: '',
            join_date: '',
            employment_status: 'active',
            department_id: null,
            position_id: null,
        },
        deleteTarget: {
            employee_id: null,
            full_name: '',
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
                employee_id: null,
                employee_number: '',
                full_name: '',
                gender: '',
                birth_date: '',
                phone: '',
                address: '',
                join_date: '',
                employment_status: 'active',
                department_id: null,
                position_id: null,
            };
            this.showFormModal = true;
        },

        openEdit(employee) {
            this.mode = 'edit';
            this.form = {
                employee_id: employee.employee_id,
                employee_number: employee.employee_number,
                full_name: employee.full_name,
                gender: employee.gender,
                birth_date: employee.birth_date,
                phone: employee.phone ?? '',
                address: employee.address ?? '',
                join_date: employee.join_date,
                employment_status: employee.employment_status,
                department_id: employee.department_id,
                position_id: employee.position_id,
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
        },

        openDelete(employee) {
            this.deleteTarget = {
                employee_id: employee.employee_id,
                full_name: employee.full_name,
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
                    document.getElementById('employeeTableContainer').innerHTML = response.data;
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
