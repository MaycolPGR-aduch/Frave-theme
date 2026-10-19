/**
 * Wheel of olfactory families (template-parts/components/family-wheel.php): tabs on a ring.
 * Choosing a family (click on its label or segment, arrow keys, Home, End) shows its card,
 * writes its name in the middle of the ring and turns the ring the short way round so the
 * family faces the card: to the right on wide screens, down on phones (--wheel-pointer).
 *
 *   <section data-family-wheel>
 *     <div data-wheel-dial>                     turns with --wheel-rotation
 *       <svg><path data-wheel-segment>…</svg>
 *       <div role="tablist"><button role="tab" aria-controls="…" data-number="01">…</div>
 *     </div>
 *     <span data-wheel-name></span> <span data-wheel-number></span>
 *     <div role="tabpanel" id="…">…</div>
 *   </section>
 *
 * Without JavaScript the ring stays still and every card is shown.
 */
const STEPS = { ArrowRight: 1, ArrowDown: 1, ArrowLeft: -1, ArrowUp: -1 };

function initializeWheel(wheel) {
  const dial = wheel.querySelector('[data-wheel-dial]');
  const tabs = [...wheel.querySelectorAll('[role="tab"]')];
  const panels = tabs.map((tab) => document.getElementById(tab.getAttribute('aria-controls')));
  const segments = [...wheel.querySelectorAll('[data-wheel-segment]')];
  const name = wheel.querySelector('[data-wheel-name]');
  const number = wheel.querySelector('[data-wheel-number]');
  if (!dial || !tabs.length || panels.includes(null)) return;

  const step = 360 / tabs.length;
  let rotation = 0;
  let current = 0;

  const pointer = () => parseFloat(getComputedStyle(wheel).getPropertyValue('--wheel-pointer')) || 90;

  const turn = () => {
    const target = pointer() - (current + 0.5) * step;
    // Shortest way from the current angle, so the ring never spins a whole turn.
    rotation += ((((target - rotation) % 360) + 540) % 360) - 180;
    dial.style.setProperty('--wheel-rotation', `${rotation}deg`);
  };

  const select = (index, focus = false) => {
    current = (index + tabs.length) % tabs.length;
    tabs.forEach((tab, i) => {
      const active = i === current;
      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;
      panels[i].hidden = !active;
      segments[i]?.classList.toggle('is-active', active);
    });
    if (name) name.textContent = tabs[current].textContent.trim();
    if (number) number.textContent = tabs[current].dataset.number ?? '';
    if (focus) tabs[current].focus();
    turn();
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
  segments.forEach((segment, index) => segment.addEventListener('click', () => select(index)));

  // The card moves below the ring on phones: face the new side when the layout changes.
  let resizeFrame = 0;
  window.addEventListener('resize', () => {
    cancelAnimationFrame(resizeFrame);
    resizeFrame = requestAnimationFrame(turn);
  });

  select(0);
  // Animate from now on, not the first placement.
  requestAnimationFrame(() => wheel.classList.add('is-ready'));
}

export function initializeFamilyWheels() {
  document.querySelectorAll('[data-family-wheel]').forEach(initializeWheel);
}
