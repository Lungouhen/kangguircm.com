import { animate, inView, stagger } from "motion";

/**
 * KangGui RCM Animation Engine
 * Uses Motion (motion.dev) for high-performance, GPU-accelerated animations
 */

document.addEventListener("DOMContentLoaded", () => {
    initDashboardAnimations();
    initPageTransitions();
    initScrollAnimations();
    initInteractiveElements();
});

/**
 * Dashboard Entry Animations
 * Staggered fade-in for stats cards and table rows
 */
function initDashboardAnimations() {
    const statCards = document.querySelectorAll(".stat-card");
    const tableRows = document.querySelectorAll("tbody tr");
    const pageHeader = document.querySelector(".page-header");

    if (pageHeader) {
        animate(pageHeader, { opacity: [0, 1], y: [20, 0] }, { duration: 0.4, easing: "ease-out" });
    }

    if (statCards.length > 0) {
        animate(
            statCards,
            { opacity: [0, 1], y: [30, 0], scale: [0.95, 1] },
            {
                delay: stagger(0.1, { start: 0.2 }),
                duration: 0.5,
                easing: "ease-out"
            }
        );
        
        // Animate numbers counting up
        statCards.forEach(card => {
            const valueEl = card.querySelector(".stat-value");
            if (valueEl) {
                const target = parseInt(valueEl.getAttribute("data-target") || "0");
                animateValue(valueEl, 0, target, 1.5);
            }
        });
    }

    if (tableRows.length > 0) {
        animate(
            tableRows,
            { opacity: [0, 1], x: [-20, 0] },
            {
                delay: stagger(0.08, { start: 0.6 }),
                duration: 0.4
            }
        );
    }
}

/**
 * Smooth Page Transitions
 * Fade out current page, fade in new content
 */
function initPageTransitions() {
    document.body.classList.add("motion-ready");
    
    // Handle navigation clicks
    document.querySelectorAll("a[href^='/']").forEach(link => {
        link.addEventListener("click", (e) => {
            const href = link.getAttribute("href");
            if (href === "#" || href.includes("#")) return;
            
            // Only animate internal links
            if (link.hostname === window.location.hostname) {
                e.preventDefault();
                
                animate(document.body, { opacity: 0 }, {
                    duration: 0.2,
                    onComplete: () => {
                        window.location.href = href;
                    }
                });
            }
        });
    });
}

/**
 * Scroll-Triggered Animations
 * Fade in elements as they enter viewport
 */
function initScrollAnimations() {
    const scrollElements = document.querySelectorAll(".animate-on-scroll");
    
    scrollElements.forEach(el => {
        el.style.opacity = "0";
        el.style.transform = "translateY(30px)";
    });

    inView(".animate-on-scroll", ({ target }) => {
        animate(target, { opacity: 1, y: 0 }, {
            duration: 0.6,
            easing: "ease-out"
        });
    });
}

/**
 * Interactive UI Elements
 * Hover effects, button presses, menu toggles
 */
function initInteractiveElements() {
    // Button hover scale effect
    const buttons = document.querySelectorAll(".btn-interactive");
    buttons.forEach(btn => {
        btn.addEventListener("mouseenter", () => {
            animate(btn, { scale: 1.05 }, { duration: 0.2, easing: "ease-out" });
        });
        btn.addEventListener("mouseleave", () => {
            animate(btn, { scale: 1 }, { duration: 0.2, easing: "ease-out" });
        });
        btn.addEventListener("mousedown", () => {
            animate(btn, { scale: 0.95 }, { duration: 0.1 });
        });
        btn.addEventListener("mouseup", () => {
            animate(btn, { scale: 1.05 }, { duration: 0.1 });
        });
    });

    // Sidebar toggle animation
    const sidebarToggle = document.getElementById("sidebar-toggle");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", () => {
            const isOpen = sidebar.getAttribute("data-open") === "true";
            
            if (isOpen) {
                // Close sidebar
                animate(sidebar, { x: ["0%", "-100%"] }, { duration: 0.3, easing: "ease-in" });
                if (overlay) {
                    animate(overlay, { opacity: [1, 0] }, {
                        duration: 0.2,
                        onComplete: () => overlay.style.display = "none"
                    });
                }
                sidebar.setAttribute("data-open", "false");
            } else {
                // Open sidebar
                sidebar.style.display = "flex";
                if (overlay) {
                    overlay.style.display = "block";
                    animate(overlay, { opacity: [0, 1] }, { duration: 0.2 });
                }
                animate(sidebar, { x: ["-100%", "0%"] }, { duration: 0.3, easing: "ease-out" });
                sidebar.setAttribute("data-open", "true");
            }
        });
    }

    // Dropdown menus
    const dropdowns = document.querySelectorAll(".dropdown-toggle");
    dropdowns.forEach(toggle => {
        toggle.addEventListener("click", (e) => {
            e.stopPropagation();
            const menu = toggle.nextElementSibling;
            if (menu && menu.classList.contains("dropdown-menu")) {
                const isVisible = menu.style.display === "block";
                
                if (!isVisible) {
                    menu.style.display = "block";
                    animate(menu, { opacity: [0, 1], y: [-10, 0], scale: [0.95, 1] }, {
                        duration: 0.15,
                        easing: "ease-out"
                    });
                } else {
                    animate(menu, { opacity: [1, 0], y: [0, -10] }, {
                        duration: 0.15,
                        onComplete: () => menu.style.display = "none"
                    });
                }
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener("click", () => {
        document.querySelectorAll(".dropdown-menu").forEach(menu => {
            if (menu.style.display === "block") {
                animate(menu, { opacity: 0, y: -10 }, {
                    duration: 0.15,
                    onComplete: () => menu.style.display = "none"
                });
            }
        });
    });
}

/**
 * Animate numeric counters
 * @param {HTMLElement} element - The element containing the number
 * @param {number} start - Starting value
 * @param {number} end - Target value
 * @param {number} duration - Animation duration in seconds
 */
function animateValue(element, start, end, duration) {
    const startTime = performance.now();
    
    const updateNumber = (currentTime) => {
        const elapsed = (currentTime - startTime) / 1000;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function (ease-out)
        const easeOut = 1 - Math.pow(1 - progress, 3);
        
        const current = Math.floor(easeOut * (end - start) + start);
        element.textContent = current.toLocaleString();
        
        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        }
    };
    
    requestAnimationFrame(updateNumber);
}

/**
 * Utility: Check for reduced motion preference
 */
function prefersReducedMotion() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
}
