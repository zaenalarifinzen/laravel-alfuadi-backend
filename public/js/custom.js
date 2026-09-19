/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

function updateThemeUI(theme) {
    const btns = document.querySelectorAll('.theme-toggle-btn');
    btns.forEach(function(btn) {
        btn.setAttribute('title', theme === 'dark' ? 'Tukar ke Tema Terang' : 'Tukar ke Tema Gelap');
    });
}

function applyTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        document.documentElement.setAttribute('data-theme', 'dark');
        document.documentElement.classList.add('dark-mode');
        if (document.body) document.body.classList.add('dark-mode');
    } else {
        document.documentElement.setAttribute('data-bs-theme', 'light');
        document.documentElement.setAttribute('data-theme', 'light');
        document.documentElement.classList.remove('dark-mode');
        if (document.body) document.body.classList.remove('dark-mode');
    }
    localStorage.setItem('theme', theme);
    updateThemeUI(theme);
}

function toggleTheme() {
    const currentTheme = localStorage.getItem('theme') || 
        (document.documentElement.classList.contains('dark-mode') ? 'dark' : 'light');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
}

// Sidebar Toggle Handler
function initSidebarToggle() {
    document.addEventListener('click', function(e) {
        // Handle both data-bs-toggle and data-toggle attributes for sidebar
        const sidebarToggleBtn = e.target.closest('[data-bs-toggle="sidebar"], [data-toggle="sidebar"]');
        if (sidebarToggleBtn) {
            e.preventDefault();
            
            // Check viewport width to determine which toggle to use
            const windowWidth = window.innerWidth;
            
            if (windowWidth <= 1024) {
                // Mobile/Tablet: toggle sidebar-show class
                document.body.classList.toggle('sidebar-show');
            } else {
                // Desktop: toggle sidebar-mini class
                document.body.classList.toggle('sidebar-mini');
            }
        }
        
        // Close sidebar when clicking on backdrop (mobile only)
        if (e.target === document.body && document.body.classList.contains('sidebar-show')) {
            // Check if clicked on the backdrop (the pseudo-element area)
            const windowWidth = window.innerWidth;
            if (windowWidth <= 1024) {
                document.body.classList.remove('sidebar-show');
            }
        }
    });
    
    // Handle window resize to manage sidebar state responsively
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            const windowWidth = window.innerWidth;
            
            if (windowWidth <= 1024) {
                // Switch to mobile mode: remove sidebar-mini, keep sidebar-show if was toggled
                document.body.classList.remove('sidebar-mini');
            } else {
                // Switch to desktop mode: remove sidebar-show, sidebar-mini for toggle
                document.body.classList.remove('sidebar-show');
            }
        }, 250); // Debounce resize events
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const activeTheme = localStorage.getItem('theme') || 
        (document.documentElement.classList.contains('dark-mode') ? 'dark' : 
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
    
    applyTheme(activeTheme);

    // Initialize sidebar toggle
    initSidebarToggle();

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.theme-toggle-btn');
        if (btn) {
            e.preventDefault();
            toggleTheme();
        }
    });
});


document.querySelectorAll("form[data-prevent-double]").forEach((form) => {
    const btn = form.querySelector('[type="submit"]');
    let submitting = false;

    form.addEventListener("submit", (e) => {
        if (submitting) {
            e.preventDefault();
            return;
        }

        if (!form.checkValidity()) {
            e.preventDefault();
            form.reportValidity();
            return;
        }

        submitting = true;

        setTimeout(() => {
            if (e.defaultPrevented) {
                submitting = false;
                return;
            }
            btn.classList.add("btn-progress");
        }, 0);
    });

    window.addEventListener("pageshow", (e) => {
        if (e.persisted) {
            submitting = false;
            btn.classList.remove("btn-progress");
        }
    });
});
