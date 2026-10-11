/**
 * Home hero video: it loads and plays once only when motion is welcome, then rests on its
 * last frame. The toggle pauses, resumes or replays it (WCAG 2.2.2: moving content that
 * lasts more than five seconds needs a way to stop it). Otherwise the poster stays.
 */
export function initializeHeroVideo() {
  const video = document.querySelector('[data-hero-video]');
  if (!video) return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const saveData = navigator.connection?.saveData === true;
  if (reduceMotion || saveData) return;

  const toggle = document.querySelector('[data-hero-toggle]');
  const icons = toggle ? Array.from(toggle.querySelectorAll('[data-hero-icon]')) : [];

  const setState = (state) => {
    if (!toggle) return;
    const labels = { pause: toggle.dataset.labelPause, play: toggle.dataset.labelPlay, replay: toggle.dataset.labelReplay };
    toggle.setAttribute('aria-label', labels[state]);
    icons.forEach((icon) => { icon.hidden = icon.dataset.heroIcon !== state; });
  };

  video.preload = 'auto';
  video.addEventListener('playing', () => {
    video.classList.add('is-playing');
    if (toggle) toggle.hidden = false;
    setState('pause');
  });
  video.addEventListener('pause', () => setState(video.ended ? 'replay' : 'play'));
  video.addEventListener('ended', () => setState('replay'));

  toggle?.addEventListener('click', () => {
    if (video.ended) {
      video.currentTime = 0;
      video.play();
    } else if (video.paused) {
      video.play();
    } else {
      video.pause();
    }
  });

  // Browsers may refuse to autoplay even muted video (power saving); the poster stays then.
  video.play().catch(() => {});
}
