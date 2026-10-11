/** Lift the floating header with a stronger shadow once the page scrolls. */
export function initializeHeaderScroll() {
  const header = document.querySelector('[data-site-header]');
  if (!header) return;

  let ticking = false;
  const update = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 8);
    ticking = false;
  };

  window.addEventListener('scroll', () => {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(update);
  }, { passive: true });
  update();
}
