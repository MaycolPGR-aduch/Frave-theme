/**
 * FRAVE wordmark with photos in its letters (template-parts/home/letters.php): while the word
 * crosses the screen, --letters-shift goes from -1 (entering at the bottom) to 1 (leaving at
 * the top), and CSS moves each letter's photo up or down with it. Only words on screen are
 * measured, once per frame. Nothing moves with reduced motion.
 *
 *   <div data-letters><span class="home-letters__tile" style="--tile-dir: 1"><img …></span>…</div>
 */
export function initializeLetters() {
  const words = [...document.querySelectorAll('[data-letters]')];
  if (!words.length || !('IntersectionObserver' in window)) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const visible = new Set();
  let frame = 0;

  const update = () => {
    frame = 0;
    const height = window.innerHeight;
    visible.forEach((word) => {
      const box = word.getBoundingClientRect();
      const progress = (height - box.top) / (height + box.height);
      const shift = Math.max(-1, Math.min(1, progress * 2 - 1));
      word.style.setProperty('--letters-shift', shift.toFixed(3));
    });
  };

  const request = () => {
    if (!frame && visible.size) frame = requestAnimationFrame(update);
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) visible.add(entry.target);
      else visible.delete(entry.target);
    });
    request();
  });
  words.forEach((word) => observer.observe(word));

  window.addEventListener('scroll', request, { passive: true });
  window.addEventListener('resize', request);
}
