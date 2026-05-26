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

      document.getElementById('btnLireHero').href = `?controller=lecteur&action=detail&id=${s.id}`;

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






/* ── CONTACT FORM ────────────────────────────────────
   Validation côté client + feedback visuel
   ───────────────────────────────────────────────────── */
(function () {
  const form = document.querySelector('.contact-form');
  if (!form) return; // pas sur la page contact

  /* Validation d'un champ : ajoute/retire la classe error */
  function validateField(field) {
    const wrap = field.closest('.cf-field');
    if (!wrap) return true;

    let valid = true;

    if (field.required && field.value.trim() === '') {
      valid = false;
    } else if (field.type === 'email' && field.value.trim() !== '') {
      valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value.trim());
    }

    wrap.classList.toggle('cf-field-error', !valid);
    wrap.classList.toggle('cf-field-ok',    valid && field.value.trim() !== '');
    return valid;
  }

  /* Validation en temps réel sur chaque champ */
  form.querySelectorAll('input, select, textarea').forEach(field => {
    field.addEventListener('blur',  () => validateField(field));
    field.addEventListener('input', () => {
      if (field.closest('.cf-field').classList.contains('cf-field-error')) {
        validateField(field);
      }
    });
  });

  /* Soumission : valide tout avant d'envoyer */
  form.addEventListener('submit', function (e) {
    let allValid = true;
    form.querySelectorAll('input:not([type=hidden]), select, textarea').forEach(field => {
      if (!validateField(field)) allValid = false;
    });

    if (!allValid) {
      e.preventDefault();
      /* Scroll vers le premier champ en erreur */
      const firstError = form.querySelector('.cf-field-error');
      if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    /* Feedback visuel sur le bouton pendant l'envoi */
    const btn = form.querySelector('.cf-submit');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
             style="animation:spin .7s linear infinite">
          <path d="M12 2a10 10 0 0 1 10 10"/>
        </svg>
        Envoi en cours…`;
    }
  });
})();




/* ════════════════════════════════════════════════════
   DETAIL ARTICLE — à ajouter à la fin de script.js
   ════════════════════════════════════════════════════ */

/* ── TOAST ── */
function showToast(msg, duration = 2800) {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

/* ── MODALS ── */
let _pendingCommentId = null;

function openModal(id, commentId = null) {
  _pendingCommentId = commentId;
  const el = document.getElementById(id);
  if (el) el.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('open');
  document.body.style.overflow = '';
}

function closeModalOutside(e, id) {
  if (e.target === e.currentTarget) closeModal(id);
}

/* ── SIGNALEMENT ── */
function submitSignal(type) {
  const msg = type === 'article'
    ? '✅ Article signalé. Merci pour votre retour.'
    : '✅ Commentaire signalé. Merci pour votre retour.';
  showToast(msg);
}

/* ── SUPPRIMER COMMENTAIRE ── */
function deleteComment() {
  if (!_pendingCommentId) return;
  const el = document.getElementById('comment-' + _pendingCommentId);
  if (!el) return;
  el.style.transition = 'opacity .3s, transform .3s';
  el.style.opacity = '0';
  el.style.transform = 'translateX(-10px)';
  setTimeout(() => {
    el.remove();
    updateCommentCount(-1);
    showToast('🗑️ Commentaire supprimé.');
  }, 300);
  _pendingCommentId = null;
}

/* ── MODIFIER COMMENTAIRE ── */
function editComment(id) {
  // Ferme toutes les zones d'édition ouvertes
  document.querySelectorAll('.dc-edit-zone').forEach(z => z.style.display = 'none');
  document.querySelectorAll('.dc-item-text').forEach(t => t.style.display = '');

  const textEl = document.getElementById('comment-text-' + id);
  const editEl = document.getElementById('dc-edit-' + id);
  const taEl   = document.getElementById('dc-edit-ta-' + id);

  if (!textEl || !editEl || !taEl) return;

  textEl.style.display = 'none';
  editEl.style.display = 'block';
  taEl.focus();
  // Place le curseur à la fin
  taEl.setSelectionRange(taEl.value.length, taEl.value.length);
}

function cancelEdit(id) {
  const textEl = document.getElementById('comment-text-' + id);
  const editEl = document.getElementById('dc-edit-' + id);
  if (textEl) textEl.style.display = '';
  if (editEl) editEl.style.display = 'none';
}

function saveEdit(id) {
  const textEl = document.getElementById('comment-text-' + id);
  const taEl   = document.getElementById('dc-edit-ta-' + id);
  const editEl = document.getElementById('dc-edit-' + id);

  if (!textEl || !taEl || !editEl) return;
  const newText = taEl.value.trim();
  if (!newText) { showToast('⚠️ Le commentaire ne peut pas être vide.'); return; }

  textEl.textContent = newText;
  textEl.style.display = '';
  editEl.style.display = 'none';
  showToast('✅ Commentaire modifié.');
}

/* ── NOUVEAU COMMENTAIRE ── */
function expandForm() {
  const actions = document.getElementById('dcFormActions');
  const ta      = document.getElementById('dcTextarea');
  if (!actions || !ta) return;
  actions.style.display = 'flex';
  ta.style.minHeight    = '80px';
}

function collapseForm() {
  const actions = document.getElementById('dcFormActions');
  const ta      = document.getElementById('dcTextarea');
  if (!actions || !ta) return;
  actions.style.display = 'none';
  ta.style.minHeight    = '40px';
  ta.value = '';
}

function autoResize(el) {
  el.style.height = 'auto';
  el.style.height = el.scrollHeight + 'px';
}

function submitComment() {
  const ta = document.getElementById('dcTextarea');
  if (!ta) return;
  const text = ta.value.trim();
  if (!text) { showToast('⚠️ Écrivez un commentaire avant de publier.'); return; }

  const list = document.getElementById('dcList');
  if (!list) return;

  // Crée un nouvel item
  const id  = Date.now();
  const div = document.createElement('div');
  div.className = 'dc-item';
  div.id = 'comment-' + id;
  div.innerHTML = `
    <div class="dc-item-avatar" style="background:#e8f7f0;color:#0f6e40">Moi</div>
    <div class="dc-item-body">
      <div class="dc-item-header">
        <div>
          <span class="dc-item-name">Moi</span>
          <span class="dc-item-date">À l'instant</span>
        </div>
        <div class="dc-item-actions">
          <button class="dc-action-btn dc-edit-btn" onclick="editComment(${id})">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Modifier
          </button>
          <button class="dc-action-btn dc-delete-btn" onclick="openModal('modalDeleteComment', ${id})">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
            Supprimer
          </button>
          <button class="dc-action-btn dc-report-btn" onclick="openModal('modalSignalComment', ${id})">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
            Signaler
          </button>
        </div>
      </div>
      <div class="dc-item-text" id="comment-text-${id}">${escapeHtml(text)}</div>
      <div class="dc-edit-zone" id="dc-edit-${id}" style="display:none">
        <textarea class="dc-edit-textarea" id="dc-edit-ta-${id}">${escapeHtml(text)}</textarea>
        <div class="dc-edit-btns">
          <button class="dc-cancel" onclick="cancelEdit(${id})">Annuler</button>
          <button class="dc-submit" onclick="saveEdit(${id})">Enregistrer</button>
        </div>
      </div>
    </div>
  `;
  list.prepend(div);
  updateCommentCount(1);
  collapseForm();
  showToast('💬 Commentaire publié !');
}

/* ── COMPTEUR COMMENTAIRES ── */
function updateCommentCount(delta) {
  const el = document.querySelector('.dc-count');
  if (!el) return;
  const current = parseInt(el.textContent) || 0;
  el.textContent = Math.max(0, current + delta);
}

/* ── PARTAGE ── */
function shareClick(btn) {
  btn.classList.add('copied');
  setTimeout(() => btn.classList.remove('copied'), 700);
}

function copyLink(btn) {
  navigator.clipboard.writeText(window.location.href).catch(() => {});
  btn.classList.add('copied');
  showToast('🔗 Lien copié !');
  setTimeout(() => btn.classList.remove('copied'), 800);
}

/* ── ÉCHAPPER HTML ── */
function escapeHtml(str) {
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}