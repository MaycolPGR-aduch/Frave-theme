/**
 * Mini-cart drawer. Opens from the header cart link and after a product is added,
 * either through WooCommerce's AJAX buttons or a page load that added one.
 * WooCommerce replaces the header link and the drawer contents through fragments,
 * so listeners are delegated and the link attributes are reapplied after refreshes.
 */
import { createDrawer } from './drawer.js';

export function initializeMiniCart() {
  const element = document.querySelector('[data-mini-cart]');
  const backdrop = document.querySelector('[data-mini-cart-backdrop]');
  if (!element || !backdrop) return;

  const status = element.querySelector('[data-mini-cart-status]');
  const title = element.querySelector('#mini-cart-title');
  const cartLinks = () => document.querySelectorAll('a.header-action--cart');

  const decorateCartLinks = () => {
    cartLinks().forEach((link) => {
      link.setAttribute('aria-haspopup', 'dialog');
      link.setAttribute('aria-controls', element.id);
      link.setAttribute('aria-expanded', String(element.classList.contains('is-open')));
    });
  };

  const drawer = createDrawer({
    element,
    backdrop,
    rootClass: 'mini-cart-is-open',
    initialFocus: () => element.querySelector('[data-mini-cart-close]'),
    fallbackFocus: () => cartLinks()[0],
    onChange: (open) => {
      if (!open && status) status.textContent = '';
      decorateCartLinks();
    },
  });

  const open = (message = '') => {
    drawer.open();
    if (status) status.textContent = message;
  };

  drawer.render(false);

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a.header-action--cart');
    if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    event.preventDefault();
    open();
  });
  element.querySelector('[data-mini-cart-close]')?.addEventListener('click', () => drawer.close());

  // WooCommerce triggers these through jQuery, which native listeners cannot see.
  const $ = window.jQuery;
  if ($) {
    const body = $(document.body);
    body.on('added_to_cart', () => open(element.dataset.addedMessage || ''));
    // The removed item's button disappears with the refreshed contents; keep focus in the drawer.
    body.on('removed_from_cart', () => {
      if (drawer.isOpen()) title?.focus();
    });
    body.on('wc_fragments_refreshed wc_fragments_loaded', decorateCartLinks);
  }

  if (element.hasAttribute('data-open-on-load')) open(element.dataset.addedMessage || '');
}
