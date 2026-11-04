(() => {
  const button = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.mobile-nav');
  if (!button || !menu) return;
  const close = () => {
    menu.hidden = true;
    button.setAttribute('aria-expanded', 'false');
    button.setAttribute('aria-label', button.dataset.openLabel);
  };
  button.addEventListener('click', () => {
    const open = button.getAttribute('aria-expanded') !== 'true';
    menu.hidden = !open;
    button.setAttribute('aria-expanded', String(open));
    button.setAttribute('aria-label', open ? button.dataset.closeLabel : button.dataset.openLabel);
  });
  menu.querySelectorAll('a').forEach(link => link.addEventListener('click', close));
  document.addEventListener('keydown', event => { if (event.key === 'Escape') close(); });
})();

(() => {
  const form = document.querySelector('[data-registration-pricing]');
  if (!form) return;
  const membership = form.querySelector('[name="membership_type"]');
  const number = form.querySelector('[name="membership_number"]');
  const surchargeRow = form.querySelector('[data-surcharge-row]');
  const total = form.querySelector('[data-total-price]');
  if (!membership || !number || !surchargeRow || !total) return;
  const base = Number(form.dataset.basePrice);
  const surcharge = Number(form.dataset.nonMemberSurcharge);
  const money = new Intl.NumberFormat(form.dataset.locale === 'en' ? 'en-GB' : 'it-IT', { style: 'currency', currency: 'EUR' });
  const update = () => {
    const nonMember = membership.value === 'none';
    surchargeRow.hidden = !nonMember;
    number.disabled = nonMember;
    number.required = Boolean(membership.value) && !nonMember;
    if (nonMember) number.value = '';
    total.textContent = money.format(base + (nonMember ? surcharge : 0));
  };
  membership.addEventListener('change', update);
  update();
})();
