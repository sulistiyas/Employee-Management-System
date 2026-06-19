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
    };
}