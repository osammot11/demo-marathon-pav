(() => {
  const button = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.mobile-nav');
  if (!button || !menu) return;

  const setOpen = (open) => {
    menu.hidden = !open;
    button.setAttribute('aria-expanded', String(open));
    button.setAttribute('aria-label', open ? button.dataset.closeLabel : button.dataset.openLabel);
  };

  button.addEventListener('click', () => setOpen(button.getAttribute('aria-expanded') !== 'true'));
  menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
  document.addEventListener('keydown', (event) => { if (event.key === 'Escape') setOpen(false); });
})();
