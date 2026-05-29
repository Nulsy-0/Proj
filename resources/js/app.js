import 'bootstrap/dist/js/bootstrap.js';
import 'bootstrap-table/dist/bootstrap-table.min.js';

// Obter o modo de preferencia a aplicalo ("dark" ou "ligth")
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


// Botões para modar de modo
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