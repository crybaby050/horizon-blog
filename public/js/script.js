/* ════════════════════════════════════════════════════
   HorizonBlog — script.js  (version nettoyée)
   ════════════════════════════════════════════════════ */

/* ════════════════════════════════════════════════════
   UTILITAIRES GLOBAUX
   ════════════════════════════════════════════════════ */

/* ── Toggle mot de passe visible/caché ── */
window.togglePassword = function (inputId, btn) {
  const input   = document.getElementById(inputId);
  const eyeShow = btn.querySelector('.eye-show');
  const eyeHide = btn.querySelector('.eye-hide');
  if (!input) return;
  if (input.type === 'password') {
    input.type            = 'text';
    eyeShow.style.display = 'none';
    eyeHide.style.display = '';
  } else {
    input.type            = 'password';
    eyeShow.style.display = '';
    eyeHide.style.display = 'none';
  }
};

/* ── Échapper HTML ── */
function escapeHtml(str) {
  return str
    .replace(/&/g,  '&amp;')
    .replace(/</g,  '&lt;')
    .replace(/>/g,  '&gt;')
    .replace(/"/g,  '&quot;')
    .replace(/'/g,  '&#039;');
}

/* ── Scroll fade (IntersectionObserver unique) ── */
(function () {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.08 });
  document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
})();


/* ════════════════════════════════════════════════════
   NAV — MENU MOBILE + DROPDOWN UTILISATEUR
   ════════════════════════════════════════════════════ */

window.toggleMenu = function () {
  const btn    = document.getElementById('hamburger');
  const drawer = document.getElementById('mobileDrawer');
  if (!btn || !drawer) return;
  btn.classList.toggle('open');
  drawer.classList.toggle('open');
  document.body.style.overflow = drawer.classList.contains('open') ? 'hidden' : '';
};

/* Fermer le drawer en cliquant un lien */
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.mobile-drawer a, .mobile-drawer-link').forEach(a => {
    a.addEventListener('click', () => {
      const btn    = document.getElementById('hamburger');
      const drawer = document.getElementById('mobileDrawer');
      if (btn)    btn.classList.remove('open');
      if (drawer) drawer.classList.remove('open');
      document.body.style.overflow = '';
    });
  });
});

/* Dropdown utilisateur connecté */
window.toggleUserDropdown = function () {
  const dropdown = document.getElementById('userDropdown');
  if (dropdown) dropdown.classList.toggle('open');
};

document.addEventListener('click', function (e) {
  const menu     = document.getElementById('userMenu');
  const dropdown = document.getElementById('userDropdown');
  if (!menu || !dropdown) return;
  if (!menu.contains(e.target)) dropdown.classList.remove('open');
});


/* ════════════════════════════════════════════════════
   HERO SLIDER
   ════════════════════════════════════════════════════ */
(function () {
  if (typeof window.heroSlides === 'undefined') return;

  const slides    = window.heroSlides;
  const heroBg    = document.getElementById('heroBg');
  const heroTitle = document.getElementById('heroTitle');
  const heroDesc  = document.getElementById('heroDesc');
  if (!heroBg || !heroTitle || !heroDesc) return;

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
      heroTitle.textContent        = s.title;
      heroDesc.textContent         = s.desc;

      const btnHero = document.getElementById('btnLireHero');
      if (btnHero) btnHero.href = `?controller=lecteur&action=detail&id=${s.id}`;

      const cardAuteur = document.getElementById('cardAuteur');
      const cardDate   = document.getElementById('cardDate');
      const cardTags   = document.getElementById('cardTags');
      if (cardAuteur) cardAuteur.textContent = s.auteur;
      if (cardDate)   cardDate.textContent   = s.date;
      if (cardTags)   cardTags.innerHTML     = s.categories.map(c => `<span class="ctag">${c}</span>`).join('');

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


/* ════════════════════════════════════════════════════
   ARTICLE SLIDER (infinite loop)
   ════════════════════════════════════════════════════ */
(function () {
  const track  = document.getElementById('articleTrack');
  const dotsEl = document.getElementById('sdots');
  if (!track || !dotsEl) return;

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

  function cardW() { return track.children[0].offsetWidth + 28; }

  function applyPos(instant) {
    const x = (aPos + OFFSET) * cardW();
    track.style.transition = instant ? 'none' : 'transform .5s cubic-bezier(.4,0,.2,1)';
    track.style.transform  = `translateX(-${x}px)`;
  }

  buildDots();
  cloneForInfinite();
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
    if (aPos < 0)            { aPos = GROUPS - 1; applyPos(true); }
    else if (aPos >= GROUPS) { aPos = 0;           applyPos(true); }
  }

  function updateDots() {
    const norm = ((aPos % GROUPS) + GROUPS) % GROUPS;
    document.querySelectorAll('.sdot').forEach((d, i) =>
      d.classList.toggle('active', i === norm)
    );
  }
})();


/* ════════════════════════════════════════════════════
   PAGE CONTACT — validation formulaire
   ════════════════════════════════════════════════════ */
(function () {
  const form = document.querySelector('.contact-form');
  if (!form) return;

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

  form.querySelectorAll('input, select, textarea').forEach(field => {
    field.addEventListener('blur',  () => validateField(field));
    field.addEventListener('input', () => {
      if (field.closest('.cf-field').classList.contains('cf-field-error'))
        validateField(field);
    });
  });

  form.addEventListener('submit', function (e) {
    let allValid = true;
    form.querySelectorAll('input:not([type=hidden]), select, textarea').forEach(field => {
      if (!validateField(field)) allValid = false;
    });
    if (!allValid) {
      e.preventDefault();
      const firstError = form.querySelector('.cf-field-error');
      if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }
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
   DETAIL ARTICLE (espace lecteur)
   ════════════════════════════════════════════════════ */

/* ── Toast ── */
function showToast(msg, duration = 2800) {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

/* ── Modals ── */
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

/* ── Signalement ── */
function submitSignal(type) {
  const msg = type === 'article'
    ? 'Article signalé. Merci pour votre retour.'
    : 'Commentaire signalé. Merci pour votre retour.';
  showToast(msg);
}

/* ── Supprimer commentaire ── */
function deleteComment() {
  if (!_pendingCommentId) return;
  const form = document.getElementById('form-delete-' + _pendingCommentId);
  if (form) form.submit();
  _pendingCommentId = null;
}

/* ── Modifier commentaire ── */
function editComment(id) {
  document.querySelectorAll('.dc-edit-zone').forEach(z => z.style.display = 'none');
  document.querySelectorAll('.dc-item-text').forEach(t => t.style.display = '');

  const textEl = document.getElementById('comment-text-' + id);
  const editEl = document.getElementById('dc-edit-' + id);
  const taEl   = document.getElementById('dc-edit-ta-' + id);
  if (!textEl || !editEl || !taEl) return;

  textEl.style.display = 'none';
  editEl.style.display = 'block';
  taEl.focus();
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

/* ── Nouveau commentaire ── */
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

/* ── Compteur commentaires ── */
function updateCommentCount(delta) {
  const el = document.querySelector('.dc-count');
  if (!el) return;
  el.textContent = Math.max(0, (parseInt(el.textContent) || 0) + delta);
}

/* ── Partage ── */
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


/* ════════════════════════════════════════════════════
   AUTH — onglets type de compte
   ════════════════════════════════════════════════════ */
(function () {
  const tabs = document.querySelectorAll('.auth-type-tab');
  if (!tabs.length) return;
  tabs.forEach(tab => {
    const radio = tab.querySelector('input[type="radio"]');
    if (!radio) return;
    if (radio.checked) tab.classList.add('active');
    radio.addEventListener('change', function () {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });
})();


/* ════════════════════════════════════════════════════
   ESPACE AUTEUR
   ════════════════════════════════════════════════════ */

/* ── Sidebar mobile ── */
window.toggleSidebar = function () {
  const sidebar = document.getElementById('auSidebar');
  const overlay = document.getElementById('auOverlay');
  const burger  = document.getElementById('auHamburger');
  if (!sidebar) return;
  sidebar.classList.toggle('open');
  if (overlay) overlay.classList.toggle('open');
  if (burger)  burger.classList.toggle('open');
  document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
};

/* ── Toast auteur ── */
function auShowToast(msg, duration = 2800) {
  const t = document.getElementById('auToast') || document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

/* ── Modal suppression article (auteur) ── */
window.openDeleteModal = function (id, titre) {
  const overlay = document.getElementById('deleteModal');
  const idInput = document.getElementById('deleteArticleId');
  const titleEl = document.getElementById('deleteArticleTitle');
  if (!overlay) return;
  if (idInput)  idInput.value      = id;
  if (titleEl)  titleEl.textContent = titre;
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.closeDeleteModal = function () {
  const overlay = document.getElementById('deleteModal');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
};

document.addEventListener('DOMContentLoaded', function () {
  /* Fermer modal auteur sur clic overlay */
  const deleteModal = document.getElementById('deleteModal');
  if (deleteModal) {
    deleteModal.addEventListener('click', e => {
      if (e.target === deleteModal) window.closeDeleteModal();
    });
  }

  /* Fermer modals admin sur clic overlay */
  ['admDeleteModal', 'admDeleteUserModal', 'admDeleteLecteurModal'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('click', e => {
        if (e.target === el) {
          el.classList.remove('open');
          document.body.style.overflow = '';
        }
      });
    }
  });
});

/* ── Graphique auteur (Chart.js) ── */
(function () {
  if (typeof window.auChartData === 'undefined') return;
  if (!document.getElementById('auChart')) return;

  function renderAuChart() {
    const data   = window.auChartData;
    const labels = data.map(d => d.mois);
    const vues   = data.map(d => parseInt(d.total_vues)  || 0);
    const arts   = data.map(d => parseInt(d.nb_articles) || 0);
    const ctx    = document.getElementById('auChart').getContext('2d');

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
          x:  { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 11 }, color: '#6b7280' } },
          y:  { position: 'left',  grid: { color: '#f0f0f0' }, ticks: { font: { family: 'Poppins', size: 11 }, color: '#6b7280' } },
          y1: { position: 'right', grid: { display: false }, ticks: { font: { family: 'Poppins', size: 11 }, color: '#4f6ef7', stepSize: 1 } },
        },
      },
    });
  }

  if (typeof Chart !== 'undefined') {
    renderAuChart();
  } else {
    const s  = document.createElement('script');
    s.src    = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js';
    s.onload = renderAuChart;
    document.head.appendChild(s);
  }
})();

/* ── Formulaire article (ajout + modifier) ── */
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

  /* Validation à la soumission */
  form.addEventListener('submit', function (e) {
    let valid = true;
    const fields = [
      { el: titreInput,                                  min: 5,  msg: 'Le titre doit faire au moins 5 caractères.' },
      { el: document.getElementById('af-desc'),          min: 10, msg: 'La description doit faire au moins 10 caractères.' },
      { el: contenuInput,                                min: 20, msg: 'Le contenu doit faire au moins 20 caractères.' },
    ];

    fields.forEach(({ el, min, msg }) => {
      if (!el) return;
      const wrap = el.closest('.au-field');
      if (!wrap) return;
      if (el.value.trim().length < min) {
        wrap.classList.add('au-field-err');
        let span = wrap.querySelector('.au-field-msg');
        if (!span) { span = document.createElement('span'); span.className = 'au-field-msg'; wrap.appendChild(span); }
        span.textContent = msg;
        valid = false;
      } else {
        wrap.classList.remove('au-field-err');
        const span = wrap.querySelector('.au-field-msg');
        if (span) span.textContent = '';
      }
    });

    const cats = form.querySelectorAll('input[name="categories[]"]:checked');
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

/* ── Toggle catégorie ── */
window.toggleCateg = function (input) {
  const label = input.closest('.au-categ-item');
  if (label) label.classList.toggle('checked', input.checked);
};

/* ── Upload images preview ── */
window.previewImages = function (input) {
  const grid = document.getElementById('previewGrid');
  if (!grid || !input.files) return;
  Array.from(input.files).forEach(file => {
    if (!file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = e => {
      const div = document.createElement('div');
      div.className = 'au-preview-item';
      div.innerHTML = `
        <img src="${e.target.result}" alt="${file.name}"/>
        <button type="button" class="au-preview-remove" onclick="removePreview(this)">✕</button>`;
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
  const dt = new DataTransfer();
  Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
  input.files = dt.files;
  window.previewImages(input);
};


/* ════════════════════════════════════════════════════
   ESPACE ADMIN
   ════════════════════════════════════════════════════ */

/* ── Sidebar mobile admin ── */
window.admToggleSidebar = function () {
  const sidebar = document.getElementById('admSidebar');
  const overlay = document.getElementById('admOverlay');
  const burger  = document.getElementById('admHamburger');
  if (!sidebar) return;
  sidebar.classList.toggle('open');
  if (overlay) overlay.classList.toggle('open');
  if (burger)  burger.classList.toggle('open');
  document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
};

/* ── Toast admin ── */
function admShowToast(msg, duration = 2800) {
  const t = document.getElementById('admToast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

/* ── Modal suppression article (admin) ── */
window.admOpenDelete = function (type, id, name) {
  const overlay = document.getElementById('admDeleteModal');
  const idInput = document.getElementById('admDeleteId');
  const nameEl  = document.getElementById('admDeleteName');
  if (!overlay) return;
  if (idInput)  idInput.value       = id;
  if (nameEl)   nameEl.textContent  = '"' + name + '"';
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};
window.admCloseDelete = function () {
  const overlay = document.getElementById('admDeleteModal');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
};

/* ── Modal suppression auteur (admin) ── */
window.admOpenDeleteUser = function (type, id, name) {
  const overlay = document.getElementById('admDeleteUserModal');
  const idInput = document.getElementById('admDeleteUserId');
  const nameEl  = document.getElementById('admDeleteUserName');
  if (!overlay) return;
  if (idInput)  idInput.value      = id;
  if (nameEl)   nameEl.textContent = name;
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};
window.admCloseDeleteUser = function () {
  const overlay = document.getElementById('admDeleteUserModal');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
};

/* ── Modal suppression lecteur (admin) ── */
window.admOpenDeleteLecteur = function (id, name) {
  const overlay = document.getElementById('admDeleteLecteurModal');
  const idInput = document.getElementById('admDeleteLecteurId');
  const nameEl  = document.getElementById('admDeleteLecteurName');
  if (!overlay) return;
  if (idInput)  idInput.value      = id;
  if (nameEl)   nameEl.textContent = name;
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};
window.admCloseDeleteLecteur = function () {
  const overlay = document.getElementById('admDeleteLecteurModal');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
};

/* ── Graphique dashboard admin (Chart.js) ── */
(function () {
  if (typeof window.admChartData === 'undefined') return;
  if (!document.getElementById('admChart')) return;

  function renderAdmChart() {
    const data   = window.admChartData;
    const labels = data.map(d => d.mois);
    const values = data.map(d => parseInt(d.nb_articles) || 0);
    const ctx    = document.getElementById('admChart').getContext('2d');

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
            callbacks: { label: ctx => 'Articles : ' + ctx.parsed.y },
          },
        },
        scales: {
          x: { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 11 }, color: '#6b7280' } },
          y: { grid: { color: '#f0f0f0' }, beginAtZero: true, ticks: { font: { family: 'Poppins', size: 11 }, color: '#6b7280', stepSize: 1 } },
        },
      },
    });
  }

  if (typeof Chart !== 'undefined') {
    renderAdmChart();
  } else {
    const s  = document.createElement('script');
    s.src    = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js';
    s.onload = renderAdmChart;
    document.head.appendChild(s);
  }
})();