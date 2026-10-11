/**
 * Stacked cards: each .stack__item sticks below the header (CSS); as the next card slides
 * over it, it shrinks and darkens slightly. The script only writes --stack-index and
 * --stack-progress (0 → 1) while a stack is on screen.
 */
export function initializeStacks() {
  const stacks = document.querySelectorAll('[data-stack]');
  if (!stacks.length) return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  stacks.forEach((stack) => {
    const items = Array.from(stack.querySelectorAll('.stack__item'));
    items.forEach((item, index) => item.style.setProperty('--stack-index', String(index)));
    if (reduceMotion || items.length < 2) return;

    let frame = 0;
    let visible = false;

    const update = () => {
      items.forEach((item, index) => {
        const nextItem = items[index + 1];
        if (!nextItem) return;
        const current = item.getBoundingClientRect();
        const following = nextItem.getBoundingClientRect();
        // 0 while the next card is below this one, 1 once it reaches this card's top.
        const progress = (current.bottom - following.top) / current.height;
        item.style.setProperty('--stack-progress', Math.min(Math.max(progress, 0), 1).toFixed(3));
      });
    };

    const onScroll = () => {
      if (!visible) return;
      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(update);
    };

    new IntersectionObserver(([entry]) => {
      visible = entry.isIntersecting;
      if (visible) update();
    }).observe(stack);

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
  });
}
