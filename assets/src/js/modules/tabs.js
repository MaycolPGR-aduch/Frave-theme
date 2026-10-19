/**
 * Tabs ([data-tabs]): choosing a role="tab" button (click, arrow keys, Home, End) shows its
 * panel (aria-controls) and hides the others. Used by the olfactory pyramid
 * (template-parts/components/fragrance-pyramid.php).
 *
 *   <section data-tabs>
 *     <div role="tablist"><button role="tab" aria-controls="a" aria-selected="true">…</div>
 *     <div role="tabpanel" id="a">…</div>
 *   </section>
 *
 * Without JavaScript every panel is shown.
 */
const STEPS = { ArrowRight: 1, ArrowDown: 1, ArrowLeft: -1, ArrowUp: -1 };

function initializeTabList(root) {
  const tabs = [...root.querySelectorAll('[role="tab"]')];
  const panels = tabs.map((tab) => document.getElementById(tab.getAttribute('aria-controls')));
  if (!tabs.length || panels.includes(null)) return;

  let current = Math.max(
    0,
    tabs.findIndex((tab) => tab.getAttribute('aria-selected') === 'true'),
  );

  const select = (index, focus = false) => {
    current = (index + tabs.length) % tabs.length;
    tabs.forEach((tab, i) => {
      const active = i === current;
      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;
      panels[i].hidden = !active;
    });
    if (focus) tabs[current].focus();
  };

  tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => select(index));
    tab.addEventListener('keydown', (event) => {
      let next = null;
      if (event.key in STEPS) next = current + STEPS[event.key];
      else if (event.key === 'Home') next = 0;
      else if (event.key === 'End') next = tabs.length - 1;
      if (next === null) return;
      event.preventDefault();
      select(next, true);
    });
  });

  select(current);
  root.classList.add('is-ready');
}

export function initializeTabs() {
  document.querySelectorAll('[data-tabs]').forEach(initializeTabList);
}
