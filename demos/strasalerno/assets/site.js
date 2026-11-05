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

    const form = document.querySelector('[data-group-form]');
    if (!form) return;
    const maxParticipants = Math.min(10, Math.max(1, Number(form.dataset.maxParticipants || 10)));
    const list = form.querySelector('[data-participants]');
    const template = document.querySelector('#participant-template');
    const addButton = form.querySelector('[data-add-participant]');
    const count = form.querySelector('[data-people-count]');
    const total = form.querySelector('[data-group-total]');
    const formatter = new Intl.NumberFormat(document.documentElement.lang === 'en' ? 'en-IE' : 'it-IT', {
        style: 'currency', currency: 'EUR',
    });

    function refreshCard(card) {
        const race = card.querySelector('[data-race-select]');
        const competitive = race.selectedOptions[0]?.dataset.competitive === '1';
        const section = card.querySelector('[data-competitive-fields]');
        section.hidden = !competitive;
        section.querySelectorAll('input, select').forEach(input => { input.disabled = !competitive; });
        if (competitive) {
            const membership = card.querySelector('[data-membership-select]');
            const file = card.querySelector('[data-medical-file]');
            const required = membership.value !== 'fidal';
            membership.required = true;
            card.querySelector('[data-membership-number]').required = true;
            card.querySelector('[data-medical-expiry]').required = required;
            file.required = required;
            card.querySelector('[data-medical-attestation]').required = required || file.files.length > 0;
        }
        card.querySelector('[data-person-price]').textContent = formatter.format(Number(race.selectedOptions[0]?.dataset.price || 0));
    }

    function refresh() {
        const cards = [...list.querySelectorAll('[data-participant]')];
        let cents = 0;
        cards.forEach((card, index) => {
            card.querySelector('[data-participant-number]').textContent = String(index + 1).padStart(2, '0');
            card.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/participants\[(?:\d+|__INDEX__)\]/, `participants[${index}]`);
            });
            card.querySelector('[data-remove-participant]').hidden = cards.length === 1;
            refreshCard(card);
            cents += Math.round(Number(card.querySelector('[data-race-select]').selectedOptions[0]?.dataset.price || 0) * 100);
        });
        count.textContent = String(cards.length);
        total.textContent = formatter.format(cents / 100);
        addButton.hidden = cards.length >= maxParticipants;
    }

    list.addEventListener('change', event => {
        const card = event.target.closest('[data-participant]');
        if (card) refreshCard(card);
        refresh();
    });
    list.addEventListener('click', event => {
        const button = event.target.closest('[data-remove-participant]');
        if (!button) return;
        if (list.querySelectorAll('[data-participant]').length <= 1) return;
        button.closest('[data-participant]').remove();
        refresh();
    });
    addButton.addEventListener('click', () => {
        if (list.querySelectorAll('[data-participant]').length >= maxParticipants) return;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.replaceAll('__INDEX__', String(list.querySelectorAll('[data-participant]').length));
        list.appendChild(wrapper.firstElementChild);
        refresh();
        list.lastElementChild.scrollIntoView({behavior: 'smooth', block: 'start'});
    });
    refresh();
});
