import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';

/**
 * KangGui RCM SaaS Platform - Main JavaScript Entry Point
 */
window.Alpine = Alpine;

// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (sidebar && backdrop) {
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }
}

// User menu toggle
function toggleUserMenu() {
    const menu = document.getElementById('user-menu');
    if (menu) menu.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('user-menu');
    const userButton = event.target.closest('[onclick*="toggleUserMenu"]');
    if (userMenu && !userButton && !userMenu.contains(event.target)) {
        userMenu.classList.add('hidden');
    }
});

Alpine.start();

if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    AOS.init({ duration: 600, once: true, offset: 40 });
}
