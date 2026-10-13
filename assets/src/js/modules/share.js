/**
 * Share links of an article: «Copiar enlace» copies the address and confirms it for a
 * moment (and to screen readers), and «Compartir…» opens the phone's own share sheet where
 * the browser offers one (on touch screens: on a computer the links already cover it). Both
 * stay hidden without JavaScript or without support.
 */
export function initializeShare() {
  document.querySelectorAll('[data-share]').forEach((share) => {
    const url = share.dataset.shareUrl || window.location.href;
    const title = share.dataset.shareTitle || document.title;
    const copied = share.dataset.shareCopied || 'Enlace copiado';
    const status = share.querySelector('[data-share-status]');

    const native = share.querySelector('[data-share-native]');
    if (native && typeof navigator.share === 'function' && window.matchMedia('(pointer: coarse)').matches) {
      native.hidden = false;
      native.querySelector('button').addEventListener('click', () => {
        navigator.share({ title, url }).catch(() => {});
      });
    }

    const copy = share.querySelector('[data-share-copy]');
    if (copy && navigator.clipboard && window.isSecureContext) {
      const label = copy.querySelector('[data-share-copy-label]');
      const original = label.textContent;
      let timer = 0;
      copy.hidden = false;
      copy.querySelector('button').addEventListener('click', async () => {
        try {
          await navigator.clipboard.writeText(url);
        } catch {
          return;
        }
        label.textContent = copied;
        if (status) status.textContent = copied;
        clearTimeout(timer);
        timer = setTimeout(() => {
          label.textContent = original;
          if (status) status.textContent = '';
        }, 2500);
      });
    }
  });
}
