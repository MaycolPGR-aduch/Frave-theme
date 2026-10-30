/**
 * Notes of a perfumery subcategory (template-parts/components/fragrance-notes.php): a chip
 * shows its note in the card (name, level, family, text and the perfumes that carry it), and
 * «Sellos del estilo» keeps only the hallmarks (a hidden note in the card gives way to the
 * first hallmark). The chips carry the card's data in data-* attributes.
 *
 *   <section data-notes><button data-notes-filter="seals" aria-pressed>…</button>
 *   <button class="note-chip" data-name data-family data-url data-tone data-level data-text
 *   data-perfumes [data-seal]>…</button>…<div class="notes-card">…</div></section>
 */
const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)');

function initializeSection(section) {
  const chips = [...section.querySelectorAll('.note-chip')];
  const card = section.querySelector('.notes-card');
  if (!chips.length || !card) return;
  const part = (name) => card.querySelector(`[data-card-${name}]`);

  const show = (chip) => {
    chips.forEach((item) => item.setAttribute('aria-pressed', String(item === chip)));
    const { name, family, url, tone, level, text, perfumes } = chip.dataset;
    part('name').textContent = name;
    part('level').textContent = level;
    part('text').textContent = text;
    const link = part('family');
    link.textContent = family;
    link.href = url || '#';
    link.hidden = !url;
    part('seal').hidden = !chip.hasAttribute('data-seal');
    part('perfumes').textContent = perfumes;
    part('perfumes-wrap').hidden = !perfumes;
    const icon = part('icon');
    icon.style.setProperty('--family-tone', tone);
    icon.innerHTML = chip.querySelector('.note-chip__icon').innerHTML;
    card.classList.remove('is-changing');
    // Restart the card's entrance (pages/notes.css): reading the layout applies the removal.
    card.getBoundingClientRect();
    card.classList.add('is-changing');
  };

  // On phones the card is under the pyramid: bring it into view when it is out of sight.
  const reveal = () => {
    const box = card.getBoundingClientRect();
    if (box.top < 0 || box.bottom > window.innerHeight) {
      card.scrollIntoView({ block: 'nearest', behavior: REDUCED.matches ? 'auto' : 'smooth' });
    }
  };

  chips.forEach((chip) =>
    chip.addEventListener('click', () => {
      show(chip);
      reveal();
    }),
  );

  section.querySelectorAll('[data-notes-filter]').forEach((button, index, buttons) => {
    button.addEventListener('click', () => {
      const seals = button.dataset.notesFilter === 'seals';
      buttons.forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
      section.classList.toggle('is-seals', seals);
      const current = chips.find((chip) => chip.getAttribute('aria-pressed') === 'true');
      if (seals && current && !current.hasAttribute('data-seal')) {
        show(chips.find((chip) => chip.hasAttribute('data-seal')));
      }
    });
  });

  section.classList.add('is-ready');
}

export function initializeNotes() {
  document.querySelectorAll('[data-notes]').forEach(initializeSection);
}
