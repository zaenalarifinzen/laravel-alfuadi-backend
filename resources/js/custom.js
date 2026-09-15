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

document.addEventListener('DOMContentLoaded', function() {
    const activeTheme = localStorage.getItem('theme') || 
        (document.documentElement.classList.contains('dark-mode') ? 'dark' : 
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
    
    applyTheme(activeTheme);

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.theme-toggle-btn');
        if (btn) {
            e.preventDefault();
            toggleTheme();
        }
    });
});
