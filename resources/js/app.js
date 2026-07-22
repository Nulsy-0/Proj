import * as bootstrap from 'bootstrap/dist/js/bootstrap.js';
window.bootstrap = bootstrap;


// Get the preferred theme color ("dark" ou "light")
function getCookie(name) {
    return document.cookie
        .split('; ')
        .find(row => row.startsWith(name + '='))
        ?.split('=')[1];
}

let theme = getCookie('theme');

if (!theme) {
    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';

    document.cookie = `theme=${theme};`;
}


// Change theme color mode 
const themeBtns = document.querySelectorAll('.themeBtn');
const body = document.querySelector('body');
themeBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        body.setAttribute(
            'data-bs-theme',
            btn.id
        );
        document.cookie = `theme=${btn.id};`;
    });
});





