/**
 * Word bands that slide with the scroll: the script writes --ticker-progress (0 → 1) while
 * a [data-ticker] crosses the screen and CSS moves its rows in opposite directions. Being
 * tied to the scroll, the motion stops when the visitor stops; with reduced motion the
 * rows stay still.
 */
export function initializeTickers() {
  const tickers = document.querySelectorAll('[data-ticker]');
  if (!tickers.length) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;

  tickers.forEach((ticker) => {
    let frame = 0;
    let visible = false;

    const update = () => {
      const rect = ticker.getBoundingClientRect();
      // 0 when the band enters at the bottom of the screen, 1 when it leaves at the top.
      const progress = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
      ticker.style.setProperty('--ticker-progress', Math.min(Math.max(progress, 0), 1).toFixed(4));
    };

    const onScroll = () => {
      if (!visible) return;
      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(update);
    };

    new IntersectionObserver(([entry]) => {
      visible = entry.isIntersecting;
      if (visible) update();
    }).observe(ticker);

    ticker.classList.add('is-ticking');
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
  });
}
