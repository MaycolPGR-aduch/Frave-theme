/** Reveal editorial sections as they enter the viewport without hiding content if JS is unavailable. */
export function initializeSectionTransitions() {
  const targets = document.querySelectorAll('[data-reveal], [data-reveal-group]');

  if (!targets.length) return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduceMotion || !('IntersectionObserver' in window)) {
    targets.forEach((target) => target.classList.add('is-revealed'));
    return;
  }

  const observer = new IntersectionObserver((entries, currentObserver) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      entry.target.classList.add('is-revealed');
      currentObserver.unobserve(entry.target);
    });
  }, {
    threshold: 0.12,
    rootMargin: '0px 0px -6% 0px',
  });

  targets.forEach((target) => {
    target.classList.add('reveal-ready');
    observer.observe(target);
  });
}
