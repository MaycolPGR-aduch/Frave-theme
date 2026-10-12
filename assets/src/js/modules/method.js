/**
 * Steps of "Cómo trabajamos" ([data-method]): one step open at a time. CSS shows them as
 * tabs on large screens and as an accordion on phones; the buttons keep aria-expanded in
 * both. The address follows the open step (#paso-…), so a link can open a given step.
 */
export function initializeMethods() {
  document.querySelectorAll('[data-method]').forEach((method) => {
    const tabs = Array.from(method.querySelectorAll('[data-method-tab]'));
    if (!tabs.length) return;
    const panelOf = (tab) => document.getElementById(tab.getAttribute('aria-controls'));

    const open = (target, { updateHash = false } = {}) => {
      tabs.forEach((tab) => {
        const isOpen = tab === target;
        tab.setAttribute('aria-expanded', String(isOpen));
        panelOf(tab).hidden = !isOpen;
      });
      if (updateHash) history.replaceState(null, '', `#${target.getAttribute('aria-controls')}`);
    };

    const fromHash = () => tabs.find((tab) => `#${tab.getAttribute('aria-controls')}` === window.location.hash);

    method.classList.add('is-enhanced');
    open(fromHash() || tabs[0]);

    tabs.forEach((tab) => {
      tab.addEventListener('click', () => open(tab, { updateHash: true }));
    });

    window.addEventListener('hashchange', () => {
      const target = fromHash();
      if (target) open(target);
    });

    // A direct link to a step lands on it once the layout is ready.
    const linked = fromHash();
    if (linked) requestAnimationFrame(() => linked.scrollIntoView({ block: 'center' }));
  });
}
