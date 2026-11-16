(() => {
  const button = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.mobile-nav');
  if (button && menu) {
    const close = () => { menu.hidden = true; button.setAttribute('aria-expanded', 'false'); button.setAttribute('aria-label', button.dataset.openLabel); };
    button.addEventListener('click', () => {
      const open = button.getAttribute('aria-expanded') !== 'true';
      menu.hidden = !open;
      button.setAttribute('aria-expanded', String(open));
      button.setAttribute('aria-label', open ? button.dataset.closeLabel : button.dataset.openLabel);
    });
    menu.querySelectorAll('a').forEach(link => link.addEventListener('click', close));
    document.addEventListener('keydown', event => { if (event.key === 'Escape') close(); });
  }
  const box = document.querySelector('[data-countdown]');
  if (box) {
    const target = Date.parse(box.dataset.countdown);
    const pad = n => String(n).padStart(2, '0');
    const tick = () => {
      let left = Math.max(0, Math.floor((target - Date.now()) / 1000));
      if (left === 0) { box.classList.add('is-done'); box.querySelector('p').textContent = box.dataset.done; }
      const parts = { days: Math.floor(left / 86400), hours: Math.floor(left % 86400 / 3600), minutes: Math.floor(left % 3600 / 60), seconds: left % 60 };
      Object.entries(parts).forEach(([unit, value]) => { const el = box.querySelector('[data-unit="' + unit + '"]'); if (el) el.textContent = pad(value); });
    };
    tick();
    setInterval(tick, 1000);
  }
})();
