/**
 * Catalog filter panel: a drawer below 64rem, a plain sidebar above. Filters are links
 * and a GET form, so every choice is a normal page load with WooCommerce's parameters.
 */
import { createDrawer } from './drawer.js';

export function initializeCatalogFilters() {
  const element = document.querySelector('[data-catalog-filters]');
  const backdrop = document.querySelector('[data-catalog-filters-backdrop]');
  if (!element || !backdrop) return;

  const drawerViewport = window.matchMedia('(max-width: 63.99rem)');
  const toggles = document.querySelectorAll('[data-catalog-filters-open]');

  const drawer = createDrawer({
    element,
    backdrop,
    rootClass: 'catalog-filters-is-open',
    isModal: () => drawerViewport.matches,
    initialFocus: () => element.querySelector('.catalog-filters__close'),
    fallbackFocus: () => toggles[0],
    onChange: (open) => toggles.forEach((toggle) => toggle.setAttribute('aria-expanded', String(open))),
  });

  drawer.render(false);
  toggles.forEach((toggle) => toggle.addEventListener('click', () => drawer.open()));
  element.querySelectorAll('[data-catalog-filters-close]').forEach((button) => button.addEventListener('click', () => drawer.close()));
  drawerViewport.addEventListener('change', () => {
    drawer.close({ restoreFocus: false });
    drawer.render(false);
  });

  // Leave empty price bounds out of the URL.
  element.querySelectorAll('[data-price-filter]').forEach((form) => {
    form.addEventListener('submit', () => {
      form.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach((input) => {
        if (input.value.trim() === '') input.disabled = true;
      });
    });
  });
}
