document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-menu]');
    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            const open = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!open));
            menu.classList.toggle('open', !open);
        });
        menu.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
            toggle.setAttribute('aria-expanded', 'false');
            menu.classList.remove('open');
        }));
    }

});
