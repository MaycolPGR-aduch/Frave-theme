/**
 * Contact form of the frave-peru plugin: a character counter under the message and a
 * «Enviando…» state on the button, which also stops a second submission. The button keeps
 * its name and value (the plugin needs them), so it is never disabled.
 */
export function initializeContactForm() {
  const form = document.querySelector('.frave-contact form');
  if (!form) return;

  const message = form.querySelector('textarea[maxlength]');
  if (message) {
    const max = Number(message.getAttribute('maxlength'));
    const counter = document.createElement('span');
    counter.className = 'frave-contact__counter';
    counter.setAttribute('aria-hidden', 'true');
    const update = () => {
      counter.textContent = `${message.value.length.toLocaleString('es-PE')} / ${max.toLocaleString('es-PE')}`;
    };
    message.after(counter);
    message.addEventListener('input', update);
    update();
  }

  const button = form.querySelector('.frave-contact__submit');
  let sending = false;
  form.addEventListener('submit', (event) => {
    if (sending) {
      event.preventDefault();
      return;
    }
    sending = true;
    if (!button) return;
    button.classList.add('is-sending');
    button.setAttribute('aria-busy', 'true');
    const label = button.querySelector('.frave-contact__submit-label');
    if (label && button.dataset.sendingLabel) label.textContent = button.dataset.sendingLabel;
  });

  // Coming back with the browser's back button must not leave the form stuck.
  window.addEventListener('pageshow', (event) => {
    if (!event.persisted || !button) return;
    sending = false;
    button.classList.remove('is-sending');
    button.removeAttribute('aria-busy');
  });
}
