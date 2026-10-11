/**
 * Count figures up when they appear: <span data-count-to="40">40</span>. The final value
 * is in the HTML, so it shows without JavaScript and with reduced motion; the element
 * should be aria-hidden next to a screen reader text, as its content changes while counting.
 */
export function initializeCounters() {
  const counters = document.querySelectorAll('[data-count-to]');
  if (!counters.length) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;

  const format = new Intl.NumberFormat(document.documentElement.lang || 'es');
  const duration = 1400;

  const run = (element) => {
    const target = Number(element.dataset.countTo);
    if (!Number.isFinite(target)) return;
    const start = performance.now();
    const tick = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const eased = 1 - (1 - progress) ** 3;
      element.textContent = format.format(Math.round(target * eased));
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      observer.unobserve(entry.target);
      run(entry.target);
    });
  }, { threshold: 0.6 });

  counters.forEach((counter) => observer.observe(counter));
}
