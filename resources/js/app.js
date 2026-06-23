import './bootstrap';
import Alpine from 'alpinejs';
import roleManager from './components/roleManager';
import departmentManager from './components/departmentManager';
import positionManager from './components/positionManager';
import shiftManager from './components/shiftManager';
import userManager from './components/userManager';
import employeeManager from './components/employeeManager';

// import datatableFilter from './datatableFilter';

Alpine.data('appLayout', () => ({
    sidebarOpen: window.innerWidth >= 1024,

    isMobile() {
        return window.innerWidth < 768;
    },
}));

Alpine.data('loginForm', () => ({
    showPassword: false,
    isSubmitting: false,
    showDemoModal: false,

    demoAccounts: [
        { label: 'Super Admin', email: 'superadmin@ems.test' },
        { label: 'Director', email: 'director@ems.test' },
        { label: 'Manager', email: 'manager1@ems.test' },
        { label: 'HR', email: 'hr1@ems.test' },
        { label: 'Staff', email: 'staff1@ems.test' },
    ],

    handleSubmit() {
        this.isSubmitting = true;
        // Form submit berjalan normal (POST ke server)
        // isSubmitting akan direset otomatis setelah page reload
    },

    openDemoModal() {
        this.showDemoModal = true;
    },

    closeDemoModal() {
        this.showDemoModal = false;
    },

    fillDemoAccount(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
        this.closeDemoModal();
    },
}));

Alpine.data('roleManager', roleManager);
Alpine.data('departmentManager', departmentManager);
Alpine.data('positionManager', positionManager);
Alpine.data('shiftManager', shiftManager);
Alpine.data('userManager', userManager);
Alpine.data('employeeManager', employeeManager);

// Alpine.data('datatableFilter', datatableFilter);

window.Alpine = Alpine;
Alpine.start();