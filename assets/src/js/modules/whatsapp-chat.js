/**
 * Guided WhatsApp chat ([data-wa-chat]): the floating button opens a small panel where the
 * visitor picks a reason and adds a few details; "Continuar en WhatsApp" is a real link
 * whose wa.me message follows what they type, so nothing is sent to or stored by the site
 * and no popup blocker gets in the way. Escape or the close button return focus to the
 * floating button. Without JavaScript the button is a plain WhatsApp link.
 */
const PROMPTS = {
  quote: 'Perfecto. ¿De qué línea necesitas la cotización?',
  product: '¿Por qué producto quieres consultar?',
  other: 'Cuéntanos tu consulta y te respondemos por WhatsApp.',
};

const INTROS = {
  quote: (line) => (line ? `quisiera solicitar una cotización de la línea ${line}.` : 'quisiera solicitar una cotización.'),
  product: (product) => (product ? `quisiera consultar por el producto: ${product}` : 'quisiera consultar por un producto.'),
  other: () => 'tengo una consulta.',
};

export function initializeWhatsAppChat() {
  const chat = document.querySelector('[data-wa-chat]');
  const opener = document.querySelector('[data-wa-chat-open]');
  if (!chat || !opener || !chat.dataset.waBase) return;

  const steps = {
    reason: chat.querySelector('[data-wa-step="reason"]'),
    details: chat.querySelector('[data-wa-step="details"]'),
  };
  const fields = {
    name: chat.querySelector('[data-wa-name]'),
    line: chat.querySelector('[data-wa-line]'),
    product: chat.querySelector('[data-wa-product]'),
    message: chat.querySelector('[data-wa-message]'),
  };
  const send = chat.querySelector('[data-wa-send]');
  const prompt = chat.querySelector('[data-wa-prompt]');
  let reason = 'other';

  if (chat.dataset.product) fields.product.value = chat.dataset.product;

  const message = () => {
    const name = fields.name.value.trim();
    const detail = { quote: fields.line.value, product: fields.product.value.trim(), other: '' }[reason];
    const greeting = name ? `Hola, soy ${name}, ` : 'Hola, ';
    const extra = fields.message.value.trim();
    return `${greeting}${INTROS[reason](detail)}${extra ? `\n\n${extra}` : ''}`;
  };

  const update = () => {
    send.href = `${chat.dataset.waBase}?text=${encodeURIComponent(message())}`;
  };

  const showStep = (name) => {
    steps.reason.hidden = name !== 'reason';
    steps.details.hidden = name !== 'details';
  };

  const open = () => {
    showStep('reason');
    chat.hidden = false;
    opener.setAttribute('aria-expanded', 'true');
    chat.querySelector('[data-wa-reason]').focus();
  };

  const close = () => {
    chat.hidden = true;
    opener.setAttribute('aria-expanded', 'false');
    opener.focus();
  };

  opener.setAttribute('aria-expanded', 'false');
  opener.setAttribute('aria-haspopup', 'dialog');
  opener.addEventListener('click', (event) => {
    if (event.metaKey || event.ctrlKey || event.shiftKey) return;
    event.preventDefault();
    if (chat.hidden) open();
    else close();
  });

  chat.querySelectorAll('[data-wa-reason]').forEach((button) => {
    button.addEventListener('click', () => {
      reason = button.dataset.waReason;
      prompt.textContent = PROMPTS[reason];
      chat.querySelectorAll('[data-wa-for]').forEach((field) => {
        field.hidden = field.dataset.waFor !== reason;
      });
      showStep('details');
      update();
      (reason === 'quote' ? fields.line : reason === 'product' ? fields.product : fields.message).focus();
    });
  });

  chat.querySelector('[data-wa-back]').addEventListener('click', () => {
    showStep('reason');
    chat.querySelector(`[data-wa-reason="${reason}"]`).focus();
  });

  steps.details.addEventListener('input', update);
  steps.details.addEventListener('change', update);
  // Enter in a field continues in WhatsApp, like the button.
  steps.details.addEventListener('submit', (event) => {
    event.preventDefault();
    update();
    send.click();
  });

  chat.querySelector('[data-wa-chat-close]').addEventListener('click', close);
  chat.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') close();
  });
  document.addEventListener('click', (event) => {
    if (!chat.hidden && !chat.contains(event.target) && !opener.contains(event.target)) {
      chat.hidden = true;
      opener.setAttribute('aria-expanded', 'false');
    }
  });
}
