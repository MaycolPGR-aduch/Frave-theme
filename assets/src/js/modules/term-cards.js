/**
 * Portfolio cards (template-parts/components/term-card.php): pointing at a subcategory, or
 * focusing it with the keyboard, fades its image over the card's picture and names it in
 * a caption; leaving the card brings the card's own picture back.
 *
 *   <li data-term-card>
 *     <a class="line-group__media">
 *       <img …>                                       card picture, always underneath
 *       <img data-swap-layer="1" data-src="…" data-srcset="…">
 *       <span data-swap-caption hidden></span>
 *     </a>
 *     <a data-swap="1" data-swap-name="Perfumería árabe">…</a>
 *   </li>
 *
 * The layers' images download only when the pointer or the focus reaches the card. While a
 * layer is still loading, the card picture stays visible underneath it.
 *
 * Bands on phones ([data-term-band]): while at least half of the band is on screen, the
 * picture goes by itself through the card's picture and each subcategory (CYCLE_TIME each),
 * marking the step ([data-swap-step]); the button [data-swap-toggle] pauses and resumes it
 * (WCAG 2.2.2). Not with reduced motion, and not on wider screens, where the pointer leads.
 */
const CYCLE_TIME = 2600;
const PHONE = '(max-width: 63.99rem)';
export function initializeTermCards() {
  document.querySelectorAll('[data-term-card]').forEach((card) => {
    const layers = [...card.querySelectorAll('[data-swap-layer]')];
    const triggers = [...card.querySelectorAll('[data-swap]')];
    const caption = card.querySelector('[data-swap-caption]');
    if (!layers.length || !triggers.length) return;

    let loaded = false;
    const load = () => {
      if (loaded) return;
      loaded = true;
      layers.forEach((image) => {
        if (image.dataset.srcset) image.srcset = image.dataset.srcset;
        if (image.dataset.src) image.src = image.dataset.src;
      });
    };

    const show = (index, name = '') => {
      layers.forEach((image) => image.classList.toggle('is-active', image.dataset.swapLayer === index));
      triggers.forEach((trigger) => trigger.classList.toggle('is-active', trigger.dataset.swap === index));
      if (caption) {
        caption.textContent = name;
        caption.hidden = name === '';
      }
    };

    card.addEventListener('pointerenter', load);
    card.addEventListener('focusin', load);
    triggers.forEach((trigger) => {
      const activate = () => {
        load();
        show(trigger.dataset.swap, trigger.dataset.swapName);
      };
      trigger.addEventListener('pointerenter', activate);
      trigger.addEventListener('focus', activate);
    });
    card.addEventListener('pointerleave', () => show(''));
    card.addEventListener('focusout', (event) => {
      if (!card.contains(event.relatedTarget)) show('');
    });

    if (card.hasAttribute('data-term-band')) cycle(card, { load, show, layers, triggers });
  });
}

function cycle(card, { load, show, layers, triggers }) {
  const steps = [...card.querySelectorAll('[data-swap-step]')];
  const toggle = card.querySelector('[data-swap-toggle]');
  if (!toggle || !steps.length) return;
  if (!window.matchMedia(PHONE).matches || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  // Step 0 is the card's own picture; then each layer, with the name of its subcategory.
  const order = ['', ...layers.map((image) => image.dataset.swapLayer)];
  const names = Object.fromEntries(triggers.map((trigger) => [trigger.dataset.swap, trigger.dataset.swapName]));
  let step = 0;
  let timer = 0;
  let visible = false;
  let paused = false;

  const go = (index) => {
    step = index;
    show(order[step], names[order[step]] || '');
    steps.forEach((element, i) => element.classList.toggle('is-on', i === step));
  };
  const run = () => {
    if (timer || paused || !visible) return;
    load();
    timer = window.setInterval(() => go((step + 1) % order.length), CYCLE_TIME);
  };
  const stop = () => {
    window.clearInterval(timer);
    timer = 0;
  };
  const label = () => {
    toggle.setAttribute('aria-label', paused ? toggle.dataset.labelPlay : toggle.dataset.labelPause);
    toggle.querySelectorAll('[data-swap-icon]').forEach((icon) => {
      icon.hidden = icon.dataset.swapIcon !== (paused ? 'play' : 'pause');
    });
  };

  toggle.addEventListener('click', () => {
    paused = !paused;
    if (paused) stop();
    else run();
    label();
  });

  new IntersectionObserver(([entry]) => {
    visible = entry.isIntersecting;
    if (visible) run();
    else stop();
  }, { threshold: 0.5 }).observe(card.querySelector('.line-group__visual') || card);

  card.classList.add('is-cycling');
  toggle.hidden = false;
  go(0);
}
