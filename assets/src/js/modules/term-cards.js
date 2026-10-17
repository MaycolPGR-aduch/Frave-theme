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
 */
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
  });
}
