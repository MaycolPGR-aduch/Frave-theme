/**
 * Home hero motion: the video, which plays once and rests on its last frame, and the
 * title's alternating words with the aroma waves behind them, which take two rounds and
 * then rest on the first word. One toggle pauses, resumes or replays all of it (WCAG 2.2.2:
 * moving content that lasts more than five seconds needs a way to stop it).
 *
 * With reduced motion nothing moves and the poster stays. The video also stays as its
 * poster on slow connections or with data saving on, and its download starts after the
 * page has loaded, so it does not compete with the poster, fonts and styles. Phones swap in
 * the portrait version first (data-portrait: its media query and sources), so each screen
 * downloads only one video.
 */
const WORD_TIME = 2600;
const ROUNDS = 2;

export function initializeHeroVideo() {
  const hero = document.querySelector('[data-hero]');
  if (!hero || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const video = hero.querySelector('[data-hero-video]');
  const words = Array.from(hero.querySelectorAll('[data-hero-rotator] > *'));
  const toggle = hero.querySelector('[data-hero-toggle]');
  const icons = toggle ? Array.from(toggle.querySelectorAll('[data-hero-icon]')) : [];

  const saveData = navigator.connection?.saveData === true;
  const slow = /(^|-)(2g|3g)$/.test(navigator.connection?.effectiveType || '');
  const playsVideo = Boolean(video) && !saveData && !slow;
  const steps = words.length > 1 ? words.length * ROUNDS : 0;

  let ambient = false;
  let paused = false;
  let step = 0;
  let current = 0;
  let timer = 0;

  const videoRunning = () => playsVideo && video.classList.contains('is-playing') && !video.paused && !video.ended;

  const setState = () => {
    if (!toggle) return;
    let state = 'replay';
    if (paused) state = 'play';
    else if (ambient || videoRunning()) state = 'pause';
    const labels = { pause: toggle.dataset.labelPause, play: toggle.dataset.labelPlay, replay: toggle.dataset.labelReplay };
    toggle.setAttribute('aria-label', labels[state]);
    icons.forEach((icon) => { icon.hidden = icon.dataset.heroIcon !== state; });
  };

  const reveal = () => {
    if (toggle) toggle.hidden = false;
    setState();
  };

  const show = (index) => {
    if (index === current) return;
    const leaving = words[current];
    leaving.classList.remove('is-active');
    leaving.classList.add('is-leaving');
    window.setTimeout(() => leaving.classList.remove('is-leaving'), 700);
    current = index;
    words[current].classList.add('is-active');
  };

  const stopAmbient = () => {
    window.clearInterval(timer);
    ambient = false;
    hero.classList.add('is-settling');
    window.setTimeout(() => hero.classList.remove('is-ambient', 'is-settling'), 1000);
    setState();
  };

  const tick = () => {
    step += 1;
    show(step % words.length);
    if (step >= steps) stopAmbient();
  };

  const startAmbient = () => {
    if (!steps) return;
    window.clearInterval(timer);
    step = 0;
    show(0);
    ambient = true;
    hero.classList.remove('is-settling');
    hero.classList.add('is-ambient');
    timer = window.setInterval(tick, WORD_TIME);
    reveal();
  };

  const pause = () => {
    paused = true;
    window.clearInterval(timer);
    hero.classList.add('is-paused');
    if (videoRunning()) video.pause();
    setState();
  };

  const resume = () => {
    paused = false;
    hero.classList.remove('is-paused');
    if (ambient) timer = window.setInterval(tick, WORD_TIME);
    if (playsVideo && video.classList.contains('is-playing') && video.paused && !video.ended) video.play().catch(() => {});
    setState();
  };

  const replay = () => {
    if (playsVideo) {
      video.currentTime = 0;
      video.play().catch(() => {});
    }
    startAmbient();
  };

  if (playsVideo) {
    video.addEventListener('playing', () => {
      video.classList.add('is-playing');
      reveal();
    });
    video.addEventListener('pause', setState);
    video.addEventListener('ended', setState);
  }

  toggle?.addEventListener('click', () => {
    if (paused) resume();
    else if (ambient || videoRunning()) pause();
    else replay();
  });

  const usePortraitSources = () => {
    let portrait;
    try {
      portrait = JSON.parse(video.dataset.portrait || 'null');
    } catch {
      return;
    }
    if (!portrait?.sources?.length || !window.matchMedia(portrait.query).matches) return;
    video.replaceChildren(...portrait.sources.map(({ src, type }) => {
      const source = document.createElement('source');
      source.src = src;
      source.type = type;
      return source;
    }));
    video.load();
  };

  // Browsers may refuse to autoplay even muted video (power saving); the poster stays then.
  const start = () => {
    startAmbient();
    if (!playsVideo) return;
    usePortraitSources();
    video.preload = 'auto';
    video.play().catch(() => {});
  };
  if (document.readyState === 'complete') start();
  else window.addEventListener('load', start, { once: true });
}
