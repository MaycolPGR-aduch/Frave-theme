/**
 * Ingredient cards of a collection (template-parts/components/collection-profile.php). With a
 * mouse, pointing at a card turns it (CSS :hover); with the keyboard, focusing it does
 * (:focus-within). Touch screens have no hover, so a tap turns the card and another tap turns
 * it back; tapping its family link still follows it.
 *
 *   <ul data-note-cards><li data-note-card tabindex="0">…</li></ul>
 */
export function initializeNoteCards() {
  document.querySelectorAll('[data-note-cards] [data-note-card]').forEach((card) => {
    card.addEventListener('pointerup', (event) => {
      if (event.pointerType === 'mouse' || event.target.closest('a')) return;
      card.classList.toggle('is-flipped');
    });
  });
}
