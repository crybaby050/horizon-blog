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
    ? 'Article signalé. Merci pour votre retour.'
    : 'Commentaire signalé. Merci pour votre retour.';
  showToast(msg);
}

/* ── SUPPRIMER COMMENTAIRE ── */
function deleteComment() {
  if (!_pendingCommentId) return;
  const form = document.getElementById('form-delete-' + _pendingCommentId);
  if (form) form.submit();
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
  const taEl = document.getElementById('dc-edit-ta-' + id);
  if (!taEl) return;
  if (!taEl.value.trim()) { showToast('Le commentaire ne peut pas être vide.'); return; }
  taEl.closest('form').submit();
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

// Toggle user dropdown
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.classList.toggle('open');
    }
}

// Fermer le dropdown en cliquant ailleurs
document.addEventListener('click', function(e) {
    const menu = document.getElementById('userMenu');
    const dropdown = document.getElementById('userDropdown');
    if (menu && dropdown && !menu.contains(e.target)) {
        dropdown.classList.remove('open');
    }
});

/* ── TOGGLE PASSWORD ── */
function togglePassword(inputId, btn) {
  const input  = document.getElementById(inputId);
  const eyeShow = btn.querySelector('.eye-show');
  const eyeHide = btn.querySelector('.eye-hide');
  if (input.type === 'password') {
    input.type = 'text';
    eyeShow.style.display = 'none';
    eyeHide.style.display = '';
  } else {
    input.type = 'password';
    eyeShow.style.display = '';
    eyeHide.style.display = 'none';
  }
}


/* ── USER DROPDOWN ── */
function toggleUserDropdown() {
  const dropdown = document.getElementById('userDropdown');
  if (!dropdown) return;
  dropdown.classList.toggle('open');
}

// Fermer le dropdown en cliquant ailleurs
document.addEventListener('click', function(e) {
  const menu = document.getElementById('userMenu');
  const dropdown = document.getElementById('userDropdown');
  if (!menu || !dropdown) return;
  if (!menu.contains(e.target)) {
    dropdown.classList.remove('open');
  }
});




/* ════════════════════════════════════════════════════
   ESPACE AUTEUR — à ajouter à la fin de script.js
   ════════════════════════════════════════════════════ */

/* ── SIDEBAR MOBILE ── */
window.toggleSidebar = function () {
  const sidebar  = document.getElementById('auSidebar');
  const overlay  = document.getElementById('auOverlay');
  const burger   = document.getElementById('auHamburger');
  if (!sidebar) return;
  sidebar.classList.toggle('open');
  overlay.classList.toggle('open');
  burger && burger.classList.toggle('open');
  document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
};

/* ── TOAST AUTEUR ── */
function auShowToast(msg, duration = 2800) {
  const t = document.getElementById('auToast') || document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

/* ── MODAL SUPPRESSION ARTICLE ── */
window.openDeleteModal = function (id, titre) {
  const overlay  = document.getElementById('deleteModal');
  const idInput  = document.getElementById('deleteArticleId');
  const titleEl  = document.getElementById('deleteArticleTitle');
  if (!overlay) return;
  if (idInput)  idInput.value = id;
  if (titleEl)  titleEl.textContent = titre;
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.closeDeleteModal = function () {
  const overlay = document.getElementById('deleteModal');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
};

// Fermer en cliquant sur l'overlay
document.addEventListener('DOMContentLoaded', function () {
  const deleteModal = document.getElementById('deleteModal');
  if (deleteModal) {
    deleteModal.addEventListener('click', function (e) {
      if (e.target === deleteModal) closeDeleteModal();
    });
  }
});

/* ── GRAPHIQUE DASHBOARD (Chart.js CDN) ── */
(function () {
  if (typeof window.auChartData === 'undefined') return;
  if (!document.getElementById('auChart')) return;

  // Charger Chart.js dynamiquement
  const script = document.createElement('script');
  script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js';
  script.onload = function () { renderAuChart(); };
  document.head.appendChild(script);

  function renderAuChart() {
    const data   = window.auChartData;
    const labels = data.map(d => d.mois);
    const vues   = data.map(d => parseInt(d.total_vues)   || 0);
    const arts   = data.map(d => parseInt(d.nb_articles)  || 0);

    const ctx = document.getElementById('auChart').getContext('2d');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: 'Vues',
            data: vues,
            backgroundColor: 'rgba(26,158,92,.15)',
            borderColor: '#1a9e5c',
            borderWidth: 2,
            borderRadius: 6,
            yAxisID: 'y',
          },
          {
            label: 'Articles',
            data: arts,
            type: 'line',
            borderColor: '#4f6ef7',
            backgroundColor: 'rgba(79,110,247,.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#4f6ef7',
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4,
            fill: true,
            yAxisID: 'y1',
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1f2937',
            titleColor: '#fff',
            bodyColor: 'rgba(255,255,255,.8)',
            padding: 12,
            borderRadius: 10,
            callbacks: {
              label: ctx => ctx.dataset.label + ' : ' + ctx.parsed.y.toLocaleString('fr-FR'),
            },
          },
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { family: 'Poppins', size: 11 }, color: '#6b7280' },
          },
          y: {
            position: 'left',
            grid: { color: '#f0f0f0' },
            ticks: { font: { family: 'Poppins', size: 11 }, color: '#6b7280' },
          },
          y1: {
            position: 'right',
            grid: { display: false },
            ticks: {
              font: { family: 'Poppins', size: 11 }, color: '#4f6ef7',
              stepSize: 1,
            },
          },
        },
      },
    });
  }
})();

/* ── FORMULAIRE ARTICLE (ajout + modifier) ── */
(function () {
  const form = document.getElementById('articleForm');
  if (!form) return;

  /* Compteurs de caractères */
  const titreInput   = document.getElementById('af-titre');
  const contenuInput = document.getElementById('af-contenu');
  const titreCount   = document.getElementById('titreCount');
  const contenuCount = document.getElementById('contenuCount');

  function updateCount(input, counter, max) {
    if (!input || !counter) return;
    const len = input.value.length;
    counter.textContent = len;
    if (max) counter.style.color = len > max * 0.9 ? '#dc2626' : '#9ca3af';
  }

  if (titreInput) {
    updateCount(titreInput, titreCount, 255);
    titreInput.addEventListener('input', () => updateCount(titreInput, titreCount, 255));
  }
  if (contenuInput) {
    updateCount(contenuInput, contenuCount, null);
    contenuInput.addEventListener('input', () => updateCount(contenuInput, contenuCount, null));
  }

  /* Validation soumission */
  form.addEventListener('submit', function (e) {
    let valid = true;
    const fields = [
      { el: titreInput,   min: 5,  label: 'Le titre doit faire au moins 5 caractères.' },
      { el: document.getElementById('af-desc'),    min: 10, label: 'La description doit faire au moins 10 caractères.' },
      { el: contenuInput, min: 20, label: 'Le contenu doit faire au moins 20 caractères.' },
    ];

    fields.forEach(({ el, min, label }) => {
      if (!el) return;
      const wrap = el.closest('.au-field');
      if (!wrap) return;
      const len = el.value.trim().length;
      if (len < min) {
        wrap.classList.add('au-field-err');
        let msg = wrap.querySelector('.au-field-msg');
        if (!msg) { msg = document.createElement('span'); msg.className = 'au-field-msg'; wrap.appendChild(msg); }
        msg.textContent = label;
        valid = false;
      } else {
        wrap.classList.remove('au-field-err');
        const msg = wrap.querySelector('.au-field-msg');
        if (msg) msg.textContent = '';
      }
    });

    // Catégories
    const cats = form.querySelectorAll('input[name="categories[]"]:checked');
    const catWrap = form.querySelector('.au-categ-list');
    if (cats.length === 0) {
      valid = false;
      auShowToast('⚠️ Sélectionnez au moins une catégorie.');
    } else if (cats.length > 5) {
      valid = false;
      auShowToast('⚠️ Maximum 5 catégories autorisées.');
    }

    if (!valid) {
      e.preventDefault();
      const firstErr = form.querySelector('.au-field-err');
      if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    /* Feedback bouton */
    const btn = document.getElementById('submitBtn');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = `
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"
             stroke-linecap="round" style="animation:spin .7s linear infinite">
          <path d="M12 2a10 10 0 0 1 10 10"/>
        </svg>
        Envoi en cours…`;
    }
  });
})();

/* ── CATÉGORIES TOGGLE ── */
window.toggleCateg = function (input) {
  const label = input.closest('.au-categ-item');
  if (!label) return;
  label.classList.toggle('checked', input.checked);
};

/* ── UPLOAD IMAGES PREVIEW ── */
window.previewImages = function (input) {
  const grid = document.getElementById('previewGrid');
  if (!grid || !input.files) return;

  Array.from(input.files).forEach((file, idx) => {
    if (!file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = function (e) {
      const div = document.createElement('div');
      div.className = 'au-preview-item';
      div.innerHTML = `
        <img src="${e.target.result}" alt="${file.name}"/>
        <button type="button" class="au-preview-remove" onclick="removePreview(this)">✕</button>
      `;
      grid.appendChild(div);
    };
    reader.readAsDataURL(file);
  });
};

window.removePreview = function (btn) {
  btn.closest('.au-preview-item').remove();
};

window.handleDrop = function (e) {
  e.preventDefault();
  const zone  = document.getElementById('uploadZone');
  if (zone) zone.classList.remove('dragging');
  const input = document.getElementById('af-images');
  if (!input || !e.dataTransfer.files.length) return;
  // Transférer les fichiers dans l'input (crée un DataTransfer)
  const dt = new DataTransfer();
  Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
  input.files = dt.files;
  previewImages(input);
};

/* ── SCROLL FADE (auteur pages) ── */
(function () {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: .08 });
  document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
})();

/* ── MODALS COMMENTAIRES (partagé avec le layout lecteur) ── */
/* openModal / closeModal / closeModalOutside / deleteComment déjà définis
   dans la section lecteur du script — ils fonctionnent aussi côté auteur.
   On réutilise signalCommentId pour le formulaire de signalement auteur. */
(function () {
  const openOrig = window.openModal;
  window.openModal = function (id, commentId) {
    // Mise à jour du champ caché dans la modale de signalement auteur
    if (id === 'modalSignalComment') {
      const field = document.getElementById('signalCommentId');
      if (field && commentId != null) field.value = commentId;
    }
    if (typeof openOrig === 'function') openOrig(id, commentId);
    else {
      _pendingCommentId = commentId;
      const el = document.getElementById(id);
      if (el) el.classList.add('open');
      document.body.style.overflow = 'hidden';
    }
  };
})();



/* ════════════════════════════════════════════════════
   ESPACE ADMIN — à ajouter à la fin de script.js
   ════════════════════════════════════════════════════ */

/* ── SIDEBAR MOBILE ADMIN ── */
window.admToggleSidebar = function () {
  const sidebar  = document.getElementById('admSidebar');
  const overlay  = document.getElementById('admOverlay');
  const burger   = document.getElementById('admHamburger');
  if (!sidebar) return;
  sidebar.classList.toggle('open');
  if (overlay) overlay.classList.toggle('open');
  if (burger)  burger.classList.toggle('open');
  document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
};

/* ── TOAST ADMIN ── */
function admShowToast(msg, duration = 2800) {
  const t = document.getElementById('admToast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

/* ── MODAL SUPPRESSION ARTICLE ── */
window.admOpenDelete = function (type, id, name) {
  const overlay  = document.getElementById('admDeleteModal');
  const idInput  = document.getElementById('admDeleteId');
  const nameEl   = document.getElementById('admDeleteName');
  if (!overlay) return;
  if (idInput)  idInput.value = id;
  if (nameEl)   nameEl.textContent = '"' + name + '"';
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.admCloseDelete = function () {
  const overlay = document.getElementById('admDeleteModal');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
};

/* ── MODAL SUPPRESSION AUTEUR ── */
window.admOpenDeleteUser = function (type, id, name) {
  const overlay = document.getElementById('admDeleteUserModal');
  const idInput = document.getElementById('admDeleteUserId');
  const nameEl  = document.getElementById('admDeleteUserName');
  if (!overlay) return;
  if (idInput)  idInput.value = id;
  if (nameEl)   nameEl.textContent = name;
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.admCloseDeleteUser = function () {
  const overlay = document.getElementById('admDeleteUserModal');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
};

/* ── MODAL SUPPRESSION LECTEUR ── */
window.admOpenDeleteLecteur = function (id, name) {
  const overlay = document.getElementById('admDeleteLecteurModal');
  const idInput = document.getElementById('admDeleteLecteurId');
  const nameEl  = document.getElementById('admDeleteLecteurName');
  if (!overlay) return;
  if (idInput)  idInput.value = id;
  if (nameEl)   nameEl.textContent = name;
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.admCloseDeleteLecteur = function () {
  const overlay = document.getElementById('admDeleteLecteurModal');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
};

/* Fermer les modals en cliquant sur l'overlay */
document.addEventListener('DOMContentLoaded', function () {
  ['admDeleteModal','admDeleteUserModal','admDeleteLecteurModal'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('click', function (e) {
        if (e.target === el) {
          el.classList.remove('open');
          document.body.style.overflow = '';
        }
      });
    }
  });

  /* Scroll fade pour les pages admin */
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: .08 });
  document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
});

/* ── GRAPHIQUE DASHBOARD ADMIN ── */
(function () {
  if (typeof window.admChartData === 'undefined') return;
  if (!document.getElementById('admChart')) return;

  // Charger Chart.js dynamiquement si pas déjà chargé
  function renderAdmChart() {
    const data   = window.admChartData;
    const labels = data.map(d => d.mois);
    const values = data.map(d => parseInt(d.nb_articles) || 0);

    const ctx = document.getElementById('admChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Articles',
          data: values,
          backgroundColor: 'rgba(26,158,92,.15)',
          borderColor: '#1a9e5c',
          borderWidth: 2,
          borderRadius: 6,
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1f2937',
            titleColor: '#fff',
            bodyColor: 'rgba(255,255,255,.8)',
            padding: 12,
            borderRadius: 10,
            callbacks: {
              label: ctx => 'Articles : ' + ctx.parsed.y,
            },
          },
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { family: 'Poppins', size: 11 }, color: '#6b7280' },
          },
          y: {
            grid: { color: '#f0f0f0' },
            ticks: {
              font: { family: 'Poppins', size: 11 }, color: '#6b7280',
              stepSize: 1,
            },
            beginAtZero: true,
          },
        },
      },
    });
  }

  // Chart.js déjà chargé par le dashboard auteur ?
  if (typeof Chart !== 'undefined') {
    renderAdmChart();
  } else {
    const script  = document.createElement('script');
    script.src    = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js';
    script.onload = renderAdmChart;
    document.head.appendChild(script);
  }
})();