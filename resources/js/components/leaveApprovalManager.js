export default function leaveApprovalManager(rolePrefix) {
    return {
        rolePrefix: rolePrefix,

        showRejectModal: false,
        rejectTarget: {
            leave_request_id: null,
            employee_name: '',
        },
        rejectionReason: '',

        isLoadingTable: false,
        searchQuery: '',

        init() {
            this.searchQuery = new URLSearchParams(window.location.search).get('search') || '';

            window.addEventListener('popstate', () => {
                this.loadTable(window.location.href);
            });
        },

        openReject(leaveRequest) {
            this.rejectTarget = {
                leave_request_id: leaveRequest.leave_request_id,
                employee_name: leaveRequest.employee_name,
            };
            this.rejectionReason = '';
            this.showRejectModal = true;
        },

        closeRejectModal() {
            this.showRejectModal = false;
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
                    document.getElementById('leaveRequestTableContainer').innerHTML = response.data;
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