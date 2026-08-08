/**
 * KangGui RCM SaaS Platform - Main JavaScript Entry Point
 */

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

console.log('KangGui RCM initialized');
