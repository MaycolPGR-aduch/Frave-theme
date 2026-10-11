/**
 * Submenus as disclosures: the toggle button opens its submenu (a dropdown on desktop, an
 * accordion in the mobile drawer). On desktop a pointer hover also opens it through CSS;
 * Escape, a click outside or moving focus elsewhere closes it.
 */
function initializeSubmenus(navigation, mobileViewport) {
  const toggles = Array.from(navigation.querySelectorAll('[data-submenu-toggle]'));
  if (!toggles.length) return;

  const setOpen = (toggle, open) => {
    toggle.setAttribute('aria-expanded', String(open));
    toggle.closest('li')?.classList.toggle('is-open', open);
  };
  const closeAll = (except = null) => {
    toggles.filter((toggle) => toggle !== except).forEach((toggle) => setOpen(toggle, false));
  };

  toggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') !== 'true';
      closeAll(toggle);
      setOpen(toggle, open);
    });
    toggle.closest('li')?.addEventListener('focusout', (event) => {
      if (mobileViewport.matches || event.currentTarget.contains(event.relatedTarget)) return;
      setOpen(toggle, false);
    });
  });

  document.addEventListener('click', (event) => {
    if (!mobileViewport.matches && !navigation.contains(event.target)) closeAll();
  });
  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape' || mobileViewport.matches) return;
    const open = toggles.find((toggle) => toggle.getAttribute('aria-expanded') === 'true');
    if (!open) return;
    setOpen(open, false);
    open.focus();
  });
  mobileViewport.addEventListener('change', () => closeAll());
}

/** Accessible mobile navigation drawer. */
export function initializeNavigation() {
  const toggle = document.querySelector('[data-menu-toggle]');
  const navigation = document.querySelector('[data-primary-navigation]');
  const backdrop = document.querySelector('[data-menu-backdrop]');
  const toggleLabel = toggle?.querySelector('[data-menu-toggle-label]');

  if (!toggle || !navigation || !backdrop) return;

  const mobileViewport = window.matchMedia('(max-width: 63.99rem)');
  initializeSubmenus(navigation, mobileViewport);

  const setMenuState = (isOpen, restoreFocus = false) => {
    const open = isOpen && mobileViewport.matches;
    const hidden = mobileViewport.matches && !open;

    toggle.setAttribute('aria-expanded', String(open));
    const label = open ? toggle.dataset.labelClose : toggle.dataset.labelOpen;
    if (toggleLabel && label) toggleLabel.textContent = label;
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
  // Only react when the breakpoint is crossed: mobile browsers fire `resize`
  // while scrolling as the address bar shows and hides.
  mobileViewport.addEventListener('change', () => {
    const focusInMenu = navigation.contains(document.activeElement);
    setMenuState(false, focusInMenu && mobileViewport.matches);
  });
}
