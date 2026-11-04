(() => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-menu]');
  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') !== 'true';
      toggle.setAttribute('aria-expanded', String(open));
      menu.classList.toggle('is-open', open);
    });
    menu.addEventListener('click', (event) => {
      if (event.target.closest('a')) {
        toggle.setAttribute('aria-expanded', 'false');
        menu.classList.remove('is-open');
      }
    });
  }
  const form = document.querySelector('[data-registration-pricing]');
  if (!form) return;
  const total = form.querySelector('[data-total-price]');
  if (!total) return;
  const base = Number(form.dataset.basePrice || 0);
  const surcharge = Number(form.dataset.nonMemberSurcharge || 0);
  const update = () => {
    const membership = form.querySelector('select[name="membership_type"]');
    const extra = membership?.value === 'none' ? surcharge : 0;
    total.textContent = new Intl.NumberFormat(form.dataset.locale || 'it', { style: 'currency', currency: 'EUR' }).format(base + extra);
    const row = form.querySelector('[data-surcharge-row]');
    if (row) row.hidden = extra === 0;
  };
  form.addEventListener('change', update);
  update();
})();
