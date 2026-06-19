import './bootstrap';
import Alpine from 'alpinejs';
import roleManager from './components/roleManager';

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

window.Alpine = Alpine;
Alpine.start();