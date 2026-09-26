/** Accessible mobile navigation drawer. */
export function initializeNavigation() {
  const toggle = document.querySelector('[data-menu-toggle]');
  const navigation = document.querySelector('[data-primary-navigation]');
  const backdrop = document.querySelector('[data-menu-backdrop]');
  const toggleLabel = toggle?.querySelector('[data-menu-toggle-label]');

  if (!toggle || !navigation || !backdrop) return;

  const mobileViewport = window.matchMedia('(max-width: 63.99rem)');

  const setMenuState = (isOpen, restoreFocus = false) => {
    const open = isOpen && mobileViewport.matches;
    const hidden = mobileViewport.matches && !open;

    toggle.setAttribute('aria-expanded', String(open));
    if (toggleLabel) toggleLabel.textContent = open ? 'Cerrar menú' : 'Abrir menú';
    navigation.classList.toggle('is-open', open);
    navigation.inert = hidden;
    navigation.setAttribute('aria-hidden', String(hidden));
    backdrop.hidden = !open;
    document.documentElement.classList.toggle('menu-is-open', open);

    if (open) navigation.querySelector('a')?.focus();
    if (!open && restoreFocus) toggle.focus();
  };

  setMenuState(false);

  toggle.addEventListener('click', () => {
    setMenuState(toggle.getAttribute('aria-expanded') !== 'true');
  });
  backdrop.addEventListener('click', () => setMenuState(false, true));
  navigation.addEventListener('click', (event) => {
    if (event.target.closest('a')) setMenuState(false);
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
      setMenuState(false, true);
      return;
    }

    if (event.key !== 'Tab' || toggle.getAttribute('aria-expanded') !== 'true') return;

    const focusableItems = Array.from(
      navigation.querySelectorAll('a[href], button:not(:disabled), [tabindex]:not([tabindex="-1"])'),
    ).filter((item) => item.getClientRects().length > 0);

    if (!focusableItems.length) {
      event.preventDefault();
      toggle.focus();
      return;
    }

    const firstItem = focusableItems[0];
    const lastItem = focusableItems[focusableItems.length - 1];
    const focusIsOutsideNavigation = !navigation.contains(document.activeElement);

    if (event.shiftKey && (document.activeElement === firstItem || focusIsOutsideNavigation)) {
      event.preventDefault();
      lastItem.focus();
    } else if (!event.shiftKey && (document.activeElement === lastItem || focusIsOutsideNavigation)) {
      event.preventDefault();
      firstItem.focus();
    }
  });
  window.addEventListener('resize', () => {
    const focusInMenu = navigation.contains(document.activeElement);
    setMenuState(false, focusInMenu && mobileViewport.matches);
  });
}
