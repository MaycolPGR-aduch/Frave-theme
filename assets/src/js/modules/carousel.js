/**
 * Previous and next buttons for scroll-snap carousels:
 *
 *   <div class="carousel" data-carousel>
 *     <ul class="carousel__track" data-carousel-track tabindex="0" aria-label="…">…</ul>
 *     <div class="carousel__controls">
 *       <button class="carousel__button" data-carousel-prev aria-label="Anterior">…</button>
 *       <button class="carousel__button" data-carousel-next aria-label="Siguiente">…</button>
 *     </div>
 *   </div>
 *
 * The track scrolls natively (touch, trackpad, arrow keys when focused); the buttons move
 * one slide, are disabled at each end and hidden when every slide fits.
 */
export function initializeCarousels() {
  document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    const track = carousel.querySelector('[data-carousel-track]');
    const previous = carousel.querySelector('[data-carousel-prev]');
    const next = carousel.querySelector('[data-carousel-next]');
    if (!track || !previous || !next) return;

    const step = () => {
      const slide = track.firstElementChild;
      const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      return slide ? slide.getBoundingClientRect().width + gap : track.clientWidth;
    };

    const update = () => {
      const max = track.scrollWidth - track.clientWidth;
      // Right-to-left layouts report negative scroll positions.
      const position = Math.abs(track.scrollLeft);
      previous.disabled = position <= 2;
      next.disabled = position >= max - 2;
      carousel.toggleAttribute('data-carousel-static', max <= 2);
    };

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const move = (direction) => {
      track.scrollBy({ left: direction * step(), behavior: reduceMotion.matches ? 'auto' : 'smooth' });
    };

    previous.addEventListener('click', () => move(-1));
    next.addEventListener('click', () => move(1));

    let frame = 0;
    track.addEventListener('scroll', () => {
      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(update);
    }, { passive: true });
    if ('ResizeObserver' in window) {
      new ResizeObserver(update).observe(track);
    } else {
      window.addEventListener('resize', update);
    }
    update();
  });
}
