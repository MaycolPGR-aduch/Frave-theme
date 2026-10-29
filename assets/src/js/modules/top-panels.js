/**
 * Perfumery top panels (template-parts/components/top-perfumery.php): one panel is open at a
 * time, the first at the start. On wide screens with a mouse, pointing at a panel opens it
 * (they open downward, like the industry panels open sideways); everywhere, its button opens
 * it, and on phones a tap on the open one closes it (an accordion). The closed panels'
 * contents are inert, so the keyboard reaches each panel's button and then its link.
 * Without JavaScript every panel is open.
 *
 *   <ol data-top-panels><li data-top-item class="is-open"><h3><button aria-expanded>…</button></h3>
 *   <div class="top-item__panel">…</div></li>…</ol>
 */
const WIDE = window.matchMedia('(min-width: 64rem) and (hover: hover)');

function initializeList(list) {
  const items = [...list.querySelectorAll('[data-top-item]')];
  const parts = items.map((item) => ({
    item,
    button: item.querySelector('.top-item__toggle'),
    panel: item.querySelector('.top-item__panel'),
  }));
  if (!parts.length || parts.some((part) => !part.button || !part.panel)) return;

  const set = (target) => {
    parts.forEach(({ item, button, panel }) => {
      const open = item === target;
      item.classList.toggle('is-open', open);
      button.setAttribute('aria-expanded', String(open));
      panel.inert = !open;
    });
  };

  parts.forEach(({ item, button }) => {
    button.addEventListener('click', () => {
      // On wide screens one panel stays open; on phones a second tap closes it.
      const open = item.classList.contains('is-open');
      set(open && !WIDE.matches ? null : item);
    });
    item.addEventListener('pointerenter', (event) => {
      if (event.pointerType === 'mouse' && WIDE.matches) set(item);
    });
  });

  set(items.find((item) => item.classList.contains('is-open')) || items[0]);
  list.classList.add('is-ready');
}

export function initializeTopPanels() {
  document.querySelectorAll('[data-top-panels]').forEach(initializeList);
}
