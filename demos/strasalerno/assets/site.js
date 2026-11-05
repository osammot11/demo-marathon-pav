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
    const membership = document.querySelector('select[name="membership_type"]');
    if (membership) {
        const certificate = document.querySelector('input[name="medical_certificate"]');
        const updateMedical = () => {
            const required = membership.value !== '' && membership.value !== 'fidal';
            document.querySelectorAll('[data-medical-field]').forEach(field => { field.required = required; });
            const attestation = document.querySelector('[data-medical-attestation]');
            if (attestation) attestation.required = required || Boolean(certificate?.files.length);
        };
        membership.addEventListener('change', updateMedical);
        certificate?.addEventListener('change', updateMedical);
        updateMedical();
    }
});
