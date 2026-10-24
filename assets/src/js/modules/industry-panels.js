/**
 * Industry panels (template-parts/solutions/cards.php): one panel is open at a time.
 * Pointing at a panel with a mouse, or focusing it, opens it. A tap on a closed panel opens
 * it instead of following its link, so phones and tablets work as an accordion; a tap on
 * the open one leads to its page. Keyboards and screen readers always follow the link.
 *
 *   <ul data-panels><li data-panel class="is-open"><a href="…">…</a></li>…</ul>
 */
export function initializeIndustryPanels() {
  document.querySelectorAll('[data-panels]').forEach((list) => {
    const panels = [...list.querySelectorAll('[data-panel]')];
    const open = (panel) => panels.forEach((item) => item.classList.toggle('is-open', item === panel));

    panels.forEach((panel) => {
      let touched = false;
      panel.addEventListener('pointerenter', (event) => {
        if (event.pointerType === 'mouse') open(panel);
      });
      // Only keyboard focus: a tap also focuses the link, just before its click.
      panel.addEventListener('focusin', (event) => {
        if (event.target.matches(':focus-visible')) open(panel);
      });
      panel.addEventListener('pointerdown', (event) => {
        touched = event.pointerType !== 'mouse';
      });
      panel.addEventListener('click', (event) => {
        if (touched && !panel.classList.contains('is-open')) {
          event.preventDefault();
          open(panel);
        }
        touched = false;
      });
    });
  });
}
