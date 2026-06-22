import './bootstrap';
import Alpine from 'alpinejs';
import roleManager from './components/roleManager';
import departmentManager from './components/departmentManager';
import positionManager from './components/positionManager';
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

    handleSubmit() {
        this.isSubmitting = true;
        // Form submit berjalan normal (POST ke server)
        // isSubmitting akan direset otomatis setelah page reload
    },
}));

Alpine.data('roleManager', roleManager);
Alpine.data('departmentManager', departmentManager);
Alpine.data('positionManager', positionManager);
Alpine.data('userManager', userManager);
Alpine.data('employeeManager', employeeManager);

// Alpine.data('datatableFilter', datatableFilter);

window.Alpine = Alpine;
Alpine.start();