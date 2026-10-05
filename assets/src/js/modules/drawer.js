/**
 * Side drawer shared by the mini-cart and the catalog filters: open state, backdrop,
 * Escape, focus trap, focus return and one-drawer-at-a-time. Each module decides what
 * opens its drawer and what it shows.
 *
 * `isModal` lets a drawer act as a normal sidebar on wide screens: while it returns
 * false the element is left visible, interactive and without dialog semantics.
 */
const FOCUSABLE = [
  'a[href]',
  'button:not(:disabled)',
  'input:not(:disabled):not([type="hidden"])',
  'select:not(:disabled)',
  'textarea:not(:disabled)',
  'summary',
  '[tabindex]:not([tabindex="-1"])',
].join(', ');

const drawers = new Set();

export function createDrawer({
  element,
  backdrop,
  rootClass,
  initialFocus,
  fallbackFocus = () => null,
  isModal = () => true,
  onChange = () => {},
}) {
  let returnFocus = null;

  const isOpen = () => element.classList.contains('is-open');

  const render = (open) => {
    const modal = isModal();
    const hidden = modal && !open;

    element.classList.toggle('is-open', open);
    element.inert = hidden;
    if (hidden) element.setAttribute('aria-hidden', 'true');
    else element.removeAttribute('aria-hidden');
    if (modal) {
      element.setAttribute('role', 'dialog');
      element.setAttribute('aria-modal', 'true');
    } else {
      element.removeAttribute('role');
      element.removeAttribute('aria-modal');
    }
    backdrop.hidden = !open;
    document.documentElement.classList.toggle(rootClass, open);
    onChange(open);
  };

  const api = {
    isOpen,
    render,
    open() {
      if (!isModal()) return;
      drawers.forEach((other) => {
        if (other !== api && other.isOpen()) other.close({ restoreFocus: false });
      });
      // The mobile menu is another overlay: close it first.
      document.querySelector('[data-menu-toggle][aria-expanded="true"]')?.click();

      if (!isOpen()) {
        const active = document.activeElement;
        returnFocus = active instanceof HTMLElement && active !== document.body ? active : null;
      }
      render(true);
      initialFocus()?.focus();
    },
    close({ restoreFocus = true } = {}) {
      if (!isOpen()) return;
      render(false);
      if (restoreFocus) {
        // The element that opened the drawer may have been replaced (e.g. by a WooCommerce fragment).
        const target = returnFocus && document.contains(returnFocus) ? returnFocus : fallbackFocus();
        target?.focus();
      }
      returnFocus = null;
    },
  };
  drawers.add(api);

  backdrop.addEventListener('click', () => api.close());

  document.addEventListener('keydown', (event) => {
    if (!isOpen()) return;
    if (event.key === 'Escape') {
      event.preventDefault();
      api.close();
      return;
    }
    if (event.key !== 'Tab') return;

    const items = Array.from(element.querySelectorAll(FOCUSABLE)).filter((item) => item.getClientRects().length > 0);
    if (!items.length) return;
    const first = items[0];
    const last = items[items.length - 1];
    const outside = !element.contains(document.activeElement);

    if (event.shiftKey && (document.activeElement === first || outside)) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && (document.activeElement === last || outside)) {
      event.preventDefault();
      first.focus();
    }
  });

  // Modal: keep focus inside while open. WooCommerce, for example, focuses its
  // "added to cart" notice 500 ms after load, behind an open drawer.
  document.addEventListener('focusin', (event) => {
    if (isOpen() && !element.contains(event.target)) initialFocus()?.focus();
  });

  return api;
}
