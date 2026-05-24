/* ════════════════════════════════════════════════════
   HorizonBlog — script.js
   Interactions UI : hero slider, article slider, menu mobile, scroll fade
   NB : les données du hero (slides) sont injectées par PHP
        via une balise <script> dans home.php AVANT ce fichier
   ════════════════════════════════════════════════════ */

/* ── HERO SLIDER ─────────────────────────────────────
   Dépend de `window.heroSlides` injecté par PHP dans home.php
   ───────────────────────────────────────────────────── */
(function () {
  if (typeof window.heroSlides === 'undefined') return; // pas sur la page home

  const slides    = window.heroSlides;
  const heroBg    = document.getElementById('heroBg');
  const heroTitle = document.getElementById('heroTitle');
  const heroDesc  = document.getElementById('heroDesc');

  if (!heroBg) return;

  heroBg.style.transition    = 'opacity .45s';
  heroTitle.style.transition = 'opacity .4s';
  heroDesc.style.transition  = 'opacity .4s';
  heroBg.style.backgroundImage = `url('${slides[0].bg}')`;

  let hi = 0, htimer;

  window.goHero = function (i) {
    clearInterval(htimer);
    hi = ((i % slides.length) + slides.length) % slides.length;
    updateHero();
    startHeroTimer();
  };

  window.changeHero = function (d) { window.goHero(hi + d); };

  function updateHero() {
    const s = slides[hi];
    heroBg.style.opacity    = '0';
    heroTitle.style.opacity = '0';
    heroDesc.style.opacity  = '0';

    setTimeout(() => {
      heroBg.style.backgroundImage = `url('${s.bg}')`;
      heroTitle.textContent = s.title;
      heroDesc.textContent  = s.desc;

      document.getElementById('btnLireHero').href = `?action=article&id=${s.id}`;

      document.getElementById('cardAuteur').textContent = s.auteur;
      document.getElementById('cardDate').textContent   = s.date;
      const tagsWrap = document.getElementById('cardTags');
      tagsWrap.innerHTML = s.categories.map(c => `<span class="ctag">${c}</span>`).join('');

      heroBg.style.opacity    = '1';
      heroTitle.style.opacity = '1';
      heroDesc.style.opacity  = '1';
    }, 280);

    document.querySelectorAll('.hero-dot').forEach((d, i) =>
      d.classList.toggle('active', i === hi)
    );
  }

  function startHeroTimer() { htimer = setInterval(() => window.changeHero(1), 5500); }
  startHeroTimer();
  setTimeout(() => heroBg.classList.add('zoomed'), 100);
})();

/* ── ARTICLE SLIDER (infinite loop) ─────────────────── */
(function () {
  const track = document.getElementById('articleTrack');
  if (!track) return;

  const dotsEl = document.getElementById('sdots');

  function getVisible() {
    if (window.innerWidth <= 640)  return 1;
    if (window.innerWidth <= 1024) return 2;
    return 3;
  }

  let VISIBLE = getVisible();
  const ORIG  = [...track.children];
  const TOTAL = ORIG.length;
  let GROUPS  = Math.ceil(TOTAL / VISIBLE);
  let OFFSET  = VISIBLE;
  let aPos    = 0;

  function buildDots() {
    dotsEl.innerHTML = '';
    GROUPS = Math.ceil(TOTAL / VISIBLE);
    for (let i = 0; i < GROUPS; i++) {
      const d = document.createElement('button');
      d.className = 'sdot' + (i === 0 ? ' active' : '');
      d.onclick = () => gotoGroup(i);
      dotsEl.appendChild(d);
    }
  }

  function cloneForInfinite() {
    while (track.children.length > TOTAL) track.removeChild(track.firstChild);
    while (track.children.length > TOTAL) track.removeChild(track.lastChild);
    for (let i = TOTAL - VISIBLE; i < TOTAL; i++)
      track.insertBefore(ORIG[i].cloneNode(true), track.firstChild);
    for (let i = 0; i < VISIBLE; i++)
      track.appendChild(ORIG[i].cloneNode(true));
    OFFSET = VISIBLE;
  }

  function rebuildSlider() {
    aPos = 0;
    track.innerHTML = '';
    ORIG.forEach(c => track.appendChild(c.cloneNode(true)));
    cloneForInfinite();
    buildDots();
    applyPos(true);
  }

  buildDots();
  cloneForInfinite();

  function cardW() {
    const gap = 28;
    return track.children[0].offsetWidth + gap;
  }

  function applyPos(instant) {
    const x = (aPos + OFFSET) * cardW();
    track.style.transition = instant ? 'none' : 'transform .5s cubic-bezier(.4,0,.2,1)';
    track.style.transform  = `translateX(-${x}px)`;
  }
  applyPos(true);

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      const newVis = getVisible();
      if (newVis !== VISIBLE) { VISIBLE = newVis; rebuildSlider(); }
      else applyPos(true);
    }, 150);
  });

  window.slideArticles = function (dir) {
    aPos += dir;
    applyPos(false);
    track.addEventListener('transitionend', snapCheck, { once: true });
    updateDots();
  };

  function gotoGroup(g)  { aPos = g; applyPos(false); updateDots(); }

  function snapCheck() {
    if (aPos < 0)           { aPos = GROUPS - 1; applyPos(true); }
    else if (aPos >= GROUPS){ aPos = 0;           applyPos(true); }
  }

  function updateDots() {
    const norm = ((aPos % GROUPS) + GROUPS) % GROUPS;
    document.querySelectorAll('.sdot').forEach((d, i) =>
      d.classList.toggle('active', i === norm)
    );
  }
})();

/* ── MENU MOBILE ─────────────────────────────────────── */
window.toggleMenu = function () {
  const btn    = document.getElementById('hamburger');
  const drawer = document.getElementById('mobileDrawer');
  if (!btn || !drawer) return;
  btn.classList.toggle('open');
  drawer.classList.toggle('open');
  document.body.style.overflow = drawer.classList.contains('open') ? 'hidden' : '';
};

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.mobile-drawer a').forEach(a => {
    a.addEventListener('click', () => {
      const btn    = document.getElementById('hamburger');
      const drawer = document.getElementById('mobileDrawer');
      if (btn)    btn.classList.remove('open');
      if (drawer) drawer.classList.remove('open');
      document.body.style.overflow = '';
    });
  });
});

/* ── SCROLL FADE ─────────────────────────────────────── */
(function () {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: .1 });
  document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
})();