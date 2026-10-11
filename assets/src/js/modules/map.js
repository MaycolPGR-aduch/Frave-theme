/**
 * Maps on demand: Google Maps is embedded only when the visitor presses "Ver el mapa".
 * Without JavaScript the "Abrir en Google Maps" link remains.
 */
export function initializeMaps() {
  document.querySelectorAll('[data-map]').forEach((map) => {
    const button = map.querySelector('[data-map-load]');
    if (!button || !map.dataset.mapSrc) return;
    button.hidden = false;
    button.addEventListener('click', () => {
      const frame = document.createElement('iframe');
      frame.src = map.dataset.mapSrc;
      frame.title = map.dataset.mapTitle || '';
      frame.loading = 'lazy';
      frame.referrerPolicy = 'no-referrer-when-downgrade';
      frame.className = 'home-map__frame';
      map.replaceChildren(frame);
      map.classList.add('is-loaded');
      frame.focus();
    }, { once: true });
  });
}
