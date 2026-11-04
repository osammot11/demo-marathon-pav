(() => {
  const slides = [...document.querySelectorAll('.slide')];
  const counter = document.getElementById('counter');
  const bar = document.getElementById('progress-bar');
  const announcer = document.getElementById('live-announcement');
  const notesPanel = document.getElementById('notes-panel');
  const notesText = document.getElementById('notes-text');
  let current = Math.max(0, Math.min(slides.length - 1, Number(location.hash.replace('#', '')) - 1 || 0));

  function show(index, updateHash = true) {
    current = Math.max(0, Math.min(slides.length - 1, index));
    slides.forEach((slide, i) => {
      slide.classList.toggle('is-active', i === current);
      slide.setAttribute('aria-hidden', i === current ? 'false' : 'true');
    });
    counter.textContent = `${String(current + 1).padStart(2, '0')} / ${String(slides.length).padStart(2, '0')}`;
    bar.style.width = `${((current + 1) / slides.length) * 100}%`;
    announcer.textContent = `Slide ${current + 1} of ${slides.length}: ${slides[current].dataset.title}`;
    notesText.textContent = slides[current].querySelector('.notes')?.textContent.trim() || '';
    document.title = `${slides[current].dataset.title} — CRO Trail 2027`;
    if (updateHash) history.replaceState(null, '', `#${current + 1}`);
  }

  const next = () => show(current + 1);
  const previous = () => show(current - 1);
  const toggleNotes = () => { notesPanel.hidden = !notesPanel.hidden; };
  document.getElementById('next').addEventListener('click', next);
  document.getElementById('previous').addEventListener('click', previous);
  document.getElementById('notes-toggle').addEventListener('click', toggleNotes);
  document.getElementById('notes-close').addEventListener('click', () => { notesPanel.hidden = true; });
  document.getElementById('fullscreen').addEventListener('click', () => {
    if (document.fullscreenElement) document.exitFullscreen();
    else document.documentElement.requestFullscreen?.();
  });
  document.addEventListener('keydown', (event) => {
    if (event.target instanceof HTMLElement && ['INPUT', 'TEXTAREA', 'SELECT'].includes(event.target.tagName)) return;
    if (['ArrowRight', 'ArrowDown', 'PageDown', ' '].includes(event.key)) { event.preventDefault(); next(); }
    if (['ArrowLeft', 'ArrowUp', 'PageUp'].includes(event.key)) { event.preventDefault(); previous(); }
    if (event.key === 'Home') show(0);
    if (event.key === 'End') show(slides.length - 1);
    if (event.key.toLowerCase() === 'n') toggleNotes();
    if (event.key.toLowerCase() === 'f') document.getElementById('fullscreen').click();
    if (event.key === 'Escape') notesPanel.hidden = true;
  });
  window.addEventListener('hashchange', () => {
    const requested = Number(location.hash.replace('#', ''));
    if (Number.isInteger(requested) && requested >= 1 && requested <= slides.length) show(requested - 1, false);
  });
  show(current, false);
})();
