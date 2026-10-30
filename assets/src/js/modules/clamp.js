/**
 * Clamped texts ([data-clamp]): this marks the text .is-clampable, so on phones the CSS
 * shows its first lines (pages/phone-heroes.css), and adds «Leer más» after it, only while
 * the text is cut; the button shows the rest («Leer menos» hides it again). Without
 * JavaScript the whole text shows.
 *
 *   <div class="page-hero__lead" data-clamp>…</div>
 */
const LABELS = { more: 'Leer más', less: 'Leer menos' };

function initializeClamp(text, index) {
  if (!text.id) text.id = `clamp-${index + 1}`;
  const button = document.createElement('button');
  button.type = 'button';
  button.className = 'clamp-toggle';
  button.setAttribute('aria-controls', text.id);
  button.setAttribute('aria-expanded', 'false');
  button.textContent = LABELS.more;
  text.after(button);
  text.classList.add('is-clampable');

  const measure = () => {
    if (text.classList.contains('is-expanded')) return;
    button.classList.toggle('is-needed', text.scrollHeight > text.clientHeight + 1);
  };

  button.addEventListener('click', () => {
    const expanded = text.classList.toggle('is-expanded');
    button.setAttribute('aria-expanded', String(expanded));
    button.textContent = expanded ? LABELS.less : LABELS.more;
    if (!expanded) measure();
  });

  measure();
  window.addEventListener('resize', measure, { passive: true });
}

export function initializeClamps() {
  document.querySelectorAll('[data-clamp]').forEach(initializeClamp);
}
